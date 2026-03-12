<?php

declare(strict_types=1);

namespace App\Livewire\Ai;

use App\Ai\Agents\CrmAssistant;
use App\Models\AiUsageLog;
use App\Models\Team;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Ai\Responses\StreamableAgentResponse;
use Laravel\Ai\Streaming\Events\TextDelta;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

final class AiFloatingChat extends Component
{
    #[Validate('required|string|max:2000')]
    public string $message = '';

    public bool $isOpen = false;

    public bool $isLoading = false;

    public ?string $conversationId = null;

    /** @var array<int, array{role: string, content: string}> */
    public array $messages = [];

    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function sendMessage(): void
    {
        $this->validate();

        $trimmedMessage = mb_trim($this->message);

        if (in_array($trimmedMessage, ['', '0'], true)) {
            return;
        }

        if ($restriction = $this->usageRestriction()) {
            $this->addAssistantMessage($restriction);

            return;
        }

        $this->messages[] = [
            'role' => 'user',
            'content' => $this->sanitizeInput($trimmedMessage),
        ];

        $this->message = '';
        $this->isLoading = true;

        $this->js('$wire.streamResponse()');
    }

    public function streamResponse(): void
    {
        $userMessage = end($this->messages);

        if (! $userMessage || $userMessage['role'] !== 'user') {
            $this->isLoading = false;

            return;
        }

        $team = $this->resolveCurrentTeam();

        $this->applyTeamApiKey($team);

        try {
            $streamResponse = $this->streamAgentResponse($userMessage['content']);
            $fullText = $this->consumeStream($streamResponse);

            $this->conversationId = $streamResponse->conversationId ?? $this->conversationId;

            $this->messages[] = [
                'role' => 'assistant',
                'content' => $fullText,
            ];

            if ($team instanceof Team) {
                $this->logTokenUsage($team, $streamResponse);
            }
        } catch (Throwable $throwable) {
            $this->addAssistantMessage(__('Sorry, an error occurred. Please try again later.'));
            report($throwable);
        } finally {
            $this->isLoading = false;
            $this->dispatch('floating-chat-scroll-bottom');
        }
    }

    public function startNewConversation(): void
    {
        $this->conversationId = null;
        $this->messages = [];
    }

    public function render(): Factory|View
    {
        return view('livewire.ai.ai-floating-chat');
    }

    private function streamAgentResponse(string $prompt): StreamableAgentResponse
    {
        $agent = new CrmAssistant;

        if ($this->conversationId) {
            return $agent
                ->continue($this->conversationId, as: Auth::user())
                ->stream($prompt);
        }

        return $agent
            ->forUser(Auth::user())
            ->stream($prompt);
    }

    private function consumeStream(object $streamResponse): string
    {
        $fullText = '';

        $streamResponse->each(function (object $event) use (&$fullText): void {
            if ($event instanceof TextDelta) {
                $fullText .= $event->delta;

                $this->stream(
                    content: e($event->delta),
                    el: '#floating-chat-stream-target',
                );
            }
        });

        return $fullText;
    }

    private function usageRestriction(): ?string
    {
        $rateLimitKey = 'ai-floating-chat:'.Auth::id();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {
            return __('Too many messages. Please wait a moment before trying again.');
        }

        RateLimiter::hit($rateLimitKey, decaySeconds: 60);

        $team = $this->resolveCurrentTeam();

        if ($team && ! $team->setting?->gemini_api_key && ! config('ai.providers.gemini.key')) {
            return __('No AI API key configured for your team.');
        }

        if ($team && $this->isTokenLimitExceeded($team)) {
            return __('Your team has reached the monthly AI token limit.');
        }

        return null;
    }

    private function resolveCurrentTeam(): ?Team
    {
        if (! app()->bound(Team::CONTAINER_BINDING)) {
            return null;
        }

        $team = resolve(Team::CONTAINER_BINDING);

        return $team instanceof Team ? $team : null;
    }

    private function applyTeamApiKey(?Team $team): void
    {
        $teamKey = $team?->setting?->gemini_api_key;

        if ($teamKey) {
            config(['ai.providers.gemini.key' => $teamKey]);
        }
    }

    private function isTokenLimitExceeded(Team $team): bool
    {
        $limit = $team->setting?->ai_monthly_token_limit ?? 100000;

        return AiUsageLog::monthlyTokensForTeam($team->id) >= $limit;
    }

    private function logTokenUsage(Team $team, object $response): void
    {
        $usage = $response->usage ?? null;

        AiUsageLog::query()->create([
            'team_id' => $team->id,
            'user_id' => Auth::id(),
            'conversation_id' => $this->conversationId,
            'model' => 'gemini-2.5-flash',
            'input_tokens' => $usage->inputTokens ?? 0,
            'output_tokens' => $usage->outputTokens ?? 0,
        ]);
    }

    private function addAssistantMessage(string $content): void
    {
        $this->messages[] = [
            'role' => 'assistant',
            'content' => $content,
        ];

        $this->dispatch('floating-chat-scroll-bottom');
    }

    private function sanitizeInput(string $input): string
    {
        $stripped = strip_tags($input);

        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $stripped) ?? $stripped;
    }
}
