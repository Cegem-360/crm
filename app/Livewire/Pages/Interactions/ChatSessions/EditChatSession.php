<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Interactions\ChatSessions;

use App\Filament\Resources\ChatSessions\Schemas\ChatSessionForm;
use App\Models\ChatSession;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditChatSession extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?ChatSession $chatSession = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?ChatSession $chatSession = null): void
    {
        $this->chatSession = $chatSession;
        $this->form->fill($chatSession?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return ChatSessionForm::configure($schema)
            ->statePath('data')
            ->model($this->chatSession ?? ChatSession::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->chatSession?->exists) {
            $this->chatSession->update($data);
            $message = __('Chat session updated successfully.');
        } else {
            $this->chatSession = ChatSession::create($data);
            $this->form->model($this->chatSession)->saveRelationships();
            $message = __('Chat session created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.chat-sessions.view', $this->chatSession), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.interactions.chat-sessions.edit-chat-session');
    }
}
