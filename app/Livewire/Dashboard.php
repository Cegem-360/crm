<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerContact;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class Dashboard extends Component
{
    use HasCurrentTeam;

    public int $customersCount = 0;

    public int $companiesCount = 0;

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
        $this->companiesCount = Company::query()->count();
        $this->contactsCount = CustomerContact::query()->count();
        $this->activeCustomersCount = Customer::query()->where('is_active', true)->count();
    }
}
