<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Interactions\Interactions;

use App\Filament\Resources\Interactions\Schemas\InteractionForm;
use App\Models\Interaction;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditInteraction extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?Interaction $interaction = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?Interaction $interaction = null): void
    {
        $this->interaction = $interaction;
        $this->form->fill($interaction?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return InteractionForm::configure($schema)
            ->statePath('data')
            ->model($this->interaction ?? Interaction::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->interaction?->exists) {
            $this->interaction->update($data);
            $message = __('Interaction updated successfully.');
        } else {
            $this->interaction = Interaction::create($data);
            $this->form->model($this->interaction)->saveRelationships();
            $message = __('Interaction created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.interactions.view', $this->interaction), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.interactions.interactions.edit-interaction');
    }
}
