<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Reports;

use App\Livewire\Concerns\HasCurrentTeam;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ProductReports extends Component
{
    use HasCurrentTeam;

    public function render(): View
    {
        return view('livewire.pages.reports.product-reports');
    }
}
