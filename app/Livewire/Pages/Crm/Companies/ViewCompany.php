<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm\Companies;

use App\Models\Company;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewCompany extends Component
{
    public Company $company;

    public function mount(Company $company): void
    {
        $this->company = $company->load('customers');
    }

    public function render(): View
    {
        return view('livewire.pages.crm.companies.view-company');
    }
}
