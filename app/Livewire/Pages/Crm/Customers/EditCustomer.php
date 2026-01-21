<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm\Customers;

use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Models\Customer;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditCustomer extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?Customer $customer = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?Customer $customer = null): void
    {
        $this->customer = $customer;

        $this->form->fill($customer?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return CustomerForm::configure($schema)
            ->statePath('data')
            ->model($this->customer ?? Customer::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->customer?->exists) {
            $this->customer->update($data);
            $message = __('Customer updated successfully.');
        } else {
            $this->customer = Customer::create($data);
            $this->form->model($this->customer)->saveRelationships();
            $message = __('Customer created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.customers.view', $this->customer), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.crm.customers.edit-customer');
    }
}
