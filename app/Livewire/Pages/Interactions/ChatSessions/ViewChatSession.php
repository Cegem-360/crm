<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Interactions\ChatSessions;

use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\ChatSession;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewChatSession extends Component
{
    use HasCurrentTeam;

    public ChatSession $chatSession;

    public function mount(ChatSession $chatSession): void
    {
        $this->chatSession = $chatSession->load(['customer', 'user', 'messages']);
    }

    public function render(): View
    {
        return view('livewire.pages.interactions.chat-sessions.view-chat-session');
    }
}
