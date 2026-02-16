<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Opportunities;

use App\Filament\Resources\LeadOpportunities\Schemas\OpportunityInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Opportunity;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewOpportunity extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Opportunity $opportunity;

    public function mount(Opportunity $opportunity): void
    {
        $this->opportunity = $opportunity;
    }

    public function infolist(Schema $schema): Schema
    {
        return OpportunityInfolist::configure($schema)
            ->record($this->opportunity)
            ->columns(2);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.opportunities.view-opportunity');
    }
}
