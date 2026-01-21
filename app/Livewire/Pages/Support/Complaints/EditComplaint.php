<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Support\Complaints;

use App\Filament\Resources\Complaints\Schemas\ComplaintForm;
use App\Models\Complaint;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditComplaint extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?Complaint $complaint = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?Complaint $complaint = null): void
    {
        $this->complaint = $complaint;
        $this->form->fill($complaint?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return ComplaintForm::configure($schema)
            ->statePath('data')
            ->model($this->complaint ?? Complaint::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->complaint?->exists) {
            $this->complaint->update($data);
            $message = __('Complaint updated successfully.');
        } else {
            $this->complaint = Complaint::create($data);
            $this->form->model($this->complaint)->saveRelationships();
            $message = __('Complaint created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.complaints.view', $this->complaint), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.support.complaints.edit-complaint');
    }
}
