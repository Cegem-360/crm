<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Interactions\ChatSessions;

use App\Filament\Resources\ChatSessions\Schemas\ChatSessionInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\ChatSession;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewChatSession extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ChatSession $chatSession;

    public function mount(ChatSession $chatSession): void
    {
        $this->chatSession = $chatSession;
    }

    public function infolist(Schema $schema): Schema
    {
        return ChatSessionInfolist::configure($schema)
            ->record($this->chatSession)
            ->columns(2);
    }

    public function render(): View
    {
        return view('livewire.pages.interactions.chat-sessions.view-chat-session');
    }
}
