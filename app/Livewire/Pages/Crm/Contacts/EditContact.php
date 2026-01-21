<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm\Contacts;

use App\Filament\Resources\Contacts\Schemas\ContactForm;
use App\Models\CustomerContact;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditContact extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?CustomerContact $contact = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?CustomerContact $contact = null): void
    {
        $this->contact = $contact;

        $this->form->fill($contact?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return ContactForm::configure($schema)
            ->statePath('data')
            ->model($this->contact ?? CustomerContact::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->contact?->exists) {
            $this->contact->update($data);
            $message = __('Contact updated successfully.');
        } else {
            $this->contact = CustomerContact::create($data);
            $this->form->model($this->contact)->saveRelationships();
            $message = __('Contact created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.contacts.view', $this->contact), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.crm.contacts.edit-contact');
    }
}
