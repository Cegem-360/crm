<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Interactions\ChatSessions;

use App\Filament\Resources\ChatSessions\Tables\ChatSessionsTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\ChatSession;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ListChatSessions extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return ChatSessionsTable::configure($table)
            ->query(ChatSession::query())
            ->recordUrl(fn (ChatSession $record): string => route('dashboard.chat-sessions.view', ['team' => $this->team, 'chatSession' => $record]))
            ->recordActions([
                Action::make('view')
                    ->url(fn (ChatSession $record): string => route('dashboard.chat-sessions.view', ['team' => $this->team, 'chatSession' => $record]))
                    ->icon(Heroicon::Eye),
                Action::make('edit')
                    ->url(fn (ChatSession $record): string => route('dashboard.chat-sessions.edit', ['team' => $this->team, 'chatSession' => $record]))
                    ->icon(Heroicon::PencilSquare),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.interactions.chat-sessions.list-chat-sessions');
    }
}
