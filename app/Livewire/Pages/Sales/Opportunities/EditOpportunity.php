<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Opportunities;

use App\Filament\Resources\LeadOpportunities\Schemas\LeadOpportunityForm;
use App\Models\Opportunity;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditOpportunity extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?Opportunity $opportunity = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?Opportunity $opportunity = null): void
    {
        $this->opportunity = $opportunity;

        $this->form->fill($opportunity?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return LeadOpportunityForm::configure($schema)
            ->statePath('data')
            ->model($this->opportunity ?? Opportunity::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->opportunity?->exists) {
            $this->opportunity->update($data);
            $message = __('Opportunity updated successfully.');
        } else {
            $this->opportunity = Opportunity::create($data);
            $this->form->model($this->opportunity)->saveRelationships();
            $message = __('Opportunity created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.opportunities.view', $this->opportunity), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.opportunities.edit-opportunity');
    }
}
