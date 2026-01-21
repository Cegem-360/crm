<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm\Customers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewCustomer extends Component
{
    public Customer $customer;

    public function mount(Customer $customer): void
    {
        $this->customer = $customer->load(['company', 'contacts']);
    }

    public function render(): View
    {
        return view('livewire.pages.crm.customers.view-customer');
    }
}
