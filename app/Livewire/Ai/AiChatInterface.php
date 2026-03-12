<?php

declare(strict_types=1);

namespace App\Livewire\Ai;

use App\Ai\Agents\CrmAssistant;
use App\Models\AiUsageLog;
use App\Models\Team;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Ai\Responses\StreamableAgentResponse;
use Laravel\Ai\Streaming\Events\TextDelta;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

final class AiChatInterface extends Component
{
    #[Validate('required|string|max:5000')]
    public string $message = '';

    public ?string $conversationId = null;

    public bool $isLoading = false;

    public bool $showSidebar = true;

    public string $selectedModel = 'gemini-2.5-flash';

    /** @var array<int, array{role: string, content: string}> */
    public array $messages = [];

    /** @return array<string, string> */
    #[Computed]
    public function availableModels(): array
    {
        return config('ai.providers.gemini.models', []);
    }

    public function sendMessage(): void
    {
        $this->validate();

        $trimmedMessage = mb_trim($this->message);

        if (in_array($trimmedMessage, ['', '0'], true)) {
            return;
        }

        $this->ensureValidModel();

        if ($restriction = $this->usageRestriction()) {
            $this->addSystemMessage($restriction);

            return;
        }

        $this->appendUserMessage($trimmedMessage);

        $this->js('$wire.streamAiResponse()');
    }

    public function streamAiResponse(): void
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
            $errorMessage = Auth::user()?->isAdmin()
                ? __('Error: :message (in :file on line :line)', [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ])
                : __('Sorry, an error occurred. Please try again later.');

            $this->addSystemMessage($errorMessage);
            report($throwable);
        } finally {
            $this->isLoading = false;
            $this->dispatch('scroll-to-bottom');
        }
    }

    public function startNewConversation(): void
    {
        $this->conversationId = null;
        $this->messages = [];
    }

    public function loadConversation(string $conversationId): void
    {
        if (! $this->userOwnsConversation($conversationId)) {
            return;
        }

        $this->conversationId = $conversationId;

        $this->messages = DB::table('agent_conversation_messages')
            ->where('conversation_id', $conversationId)->oldest()
            ->get(['role', 'content'])
            ->map(fn (object $message): array => [
                'role' => $message->role,
                'content' => $message->content,
            ])
            ->all();

        $this->dispatch('scroll-to-bottom');
    }

    public function deleteConversation(string $conversationId): void
    {
        if (! $this->userOwnsConversation($conversationId)) {
            return;
        }

        DB::table('agent_conversation_messages')
            ->where('conversation_id', $conversationId)
            ->delete();

        DB::table('agent_conversations')
            ->where('id', $conversationId)
            ->where('user_id', Auth::id())
            ->delete();

        if ($this->conversationId === $conversationId) {
            $this->startNewConversation();
        }
    }

    public function toggleSidebar(): void
    {
        $this->showSidebar = ! $this->showSidebar;
    }

    /**
     * @return Collection<int, object{id: string, title: string, updated_at: string}>
     */
    #[Computed]
    public function conversations(): Collection
    {
        return DB::table('agent_conversations')
            ->where('user_id', Auth::id())
            ->latest('updated_at')
            ->limit(50)
            ->get(['id', 'title', 'updated_at']);
    }

    /**
     * @return array{used: int, limit: int, percentage: int}
     */
    #[Computed]
    public function tokenUsage(): array
    {
        $team = $this->resolveCurrentTeam();

        if (! $team instanceof Team) {
            return ['used' => 0, 'limit' => 0, 'percentage' => 0];
        }

        $limit = $team->setting?->ai_monthly_token_limit ?? 100000;
        $used = AiUsageLog::monthlyTokensForTeam($team->id);
        $percentage = $limit > 0 ? min(100, (int) round(($used / $limit) * 100)) : 0;

        return [
            'used' => $used,
            'limit' => $limit,
            'percentage' => $percentage,
        ];
    }

    public function render(): Factory|View
    {
        return view('livewire.ai.ai-chat-interface');
    }

    private function ensureValidModel(): void
    {
        if (! array_key_exists($this->selectedModel, $this->availableModels)) {
            $this->selectedModel = 'gemini-2.5-flash';
        }
    }

    private function usageRestriction(): ?string
    {
        $rateLimitKey = 'ai-chat:'.Auth::id();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {
            return __('Too many messages. Please wait a moment before trying again.');
        }

        RateLimiter::hit($rateLimitKey, decaySeconds: 60);

        $team = $this->resolveCurrentTeam();

        if ($team && ! $team->setting?->gemini_api_key && ! config('ai.providers.gemini.key')) {
            $settingsUrl = route('filament.admin.pages.manage-team-settings', ['tenant' => $team]);

            return __('No AI API key configured for your team. Please set it up in [Team Settings](:url).', ['url' => $settingsUrl]);
        }

        if ($team && $this->isTokenLimitExceeded($team)) {
            return __('Your team has reached the monthly AI token limit. Please contact your administrator.');
        }

        return null;
    }

    private function addSystemMessage(string $content): void
    {
        $this->messages[] = [
            'role' => 'assistant',
            'content' => $content,
        ];

        $this->dispatch('scroll-to-bottom');
    }

    private function appendUserMessage(string $rawMessage): void
    {
        $sanitizedMessage = $this->sanitizeInput($rawMessage);

        $this->messages[] = [
            'role' => 'user',
            'content' => $sanitizedMessage,
        ];

        $this->message = '';
        $this->isLoading = true;
    }

    private function streamAgentResponse(string $prompt): StreamableAgentResponse
    {
        $agent = (new CrmAssistant)->withModel($this->selectedModel);

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
                    el: '#ai-stream-target',
                );
            }
        });

        return $fullText;
    }

    private function resolveCurrentTeam(): ?Team
    {
        if (! app()->bound(Team::CONTAINER_BINDING)) {
            return null;
        }

        $team = resolve(Team::CONTAINER_BINDING);

        return $team instanceof Team ? $team : null;
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
            'model' => $this->selectedModel,
            'input_tokens' => $usage->inputTokens ?? 0,
            'output_tokens' => $usage->outputTokens ?? 0,
        ]);
    }

    private function userOwnsConversation(string $conversationId): bool
    {
        return DB::table('agent_conversations')
            ->where('id', $conversationId)
            ->where('user_id', Auth::id())
            ->exists();
    }

    private function applyTeamApiKey(?Team $team): void
    {
        $teamKey = $team?->setting?->gemini_api_key;

        if ($teamKey) {
            config(['ai.providers.gemini.key' => $teamKey]);
        }
    }

    private function sanitizeInput(string $input): string
    {
        $stripped = strip_tags($input);

        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $stripped) ?? $stripped;
    }
}
