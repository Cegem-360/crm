<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Opportunities;

use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Opportunity;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewOpportunity extends Component
{
    use HasCurrentTeam;

    public Opportunity $opportunity;

    public function mount(Opportunity $opportunity): void
    {
        $this->opportunity = $opportunity->load(['customer', 'assignedUser', 'quotes']);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.opportunities.view-opportunity');
    }
}
