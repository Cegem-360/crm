<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Reports;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CustomerReports extends Component
{
    public function render(): View
    {
        return view('livewire.pages.reports.customer-reports');
    }
}
