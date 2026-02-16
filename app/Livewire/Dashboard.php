<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\CustomerType;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Customer;
use App\Models\CustomerContact;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class Dashboard extends Component
{
    use HasCurrentTeam;

    public int $customersCount = 0;

    public int $companyCustomersCount = 0;

    public int $contactsCount = 0;

    public int $activeCustomersCount = 0;

    public function mount(): void
    {
        $this->loadStats();
    }

    public function render(): View
    {
        return view('livewire.dashboard')->layout('components.layouts.dashboard');
    }

    private function loadStats(): void
    {
        $this->customersCount = Customer::query()->count();
        $this->companyCustomersCount = Customer::query()->where('type', CustomerType::Company)->count();
        $this->contactsCount = CustomerContact::query()->count();
        $this->activeCustomersCount = Customer::query()->where('is_active', true)->count();
    }
}
