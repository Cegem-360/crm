<?php

declare(strict_types=1);

namespace App\Livewire\Ai;

use App\Ai\Agents\CrmAssistant;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

final class AiChatInterface extends Component
{
    #[Validate('required|string|max:5000')]
    public string $message = '';

    public ?string $conversationId = null;

    public bool $isLoading = false;

    /** @var array<int, array{role: string, content: string}> */
    public array $messages = [];

    public function sendMessage(): void
    {
        $this->validate();

        if (in_array(mb_trim($this->message), ['', '0'], true)) {
            return;
        }

        $this->messages[] = [
            'role' => 'user',
            'content' => $this->message,
        ];

        $userMessage = $this->message;
        $this->message = '';
        $this->isLoading = true;

        try {
            $agent = new CrmAssistant;

            if ($this->conversationId) {
                $response = $agent
                    ->continue($this->conversationId, as: Auth::user())
                    ->prompt($userMessage);
            } else {
                $response = $agent
                    ->forUser(Auth::user())
                    ->prompt($userMessage);

                $this->conversationId = $response->conversationId;
            }

            $this->messages[] = [
                'role' => 'assistant',
                'content' => $response->text,
            ];
        } catch (Throwable $exception) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => __('Sorry, an error occurred. Please try again later.'),
            ];

            report($exception);
        }

        $this->isLoading = false;
        $this->dispatch('scroll-to-bottom');
    }

    public function startNewConversation(): void
    {
        $this->conversationId = null;
        $this->messages = [];
    }

    public function render(): Factory|View
    {
        return view('livewire.ai.ai-chat-interface');
    }
}
