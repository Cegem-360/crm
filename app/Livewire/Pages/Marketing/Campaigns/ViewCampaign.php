<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Marketing\Campaigns;

use App\Filament\Resources\Campaigns\Schemas\CampaignInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Campaign;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewCampaign extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Campaign $campaign;

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign;
    }

    public function infolist(Schema $schema): Schema
    {
        return CampaignInfolist::configure($schema)
            ->record($this->campaign)
            ->columns(2);
    }

    public function render(): View
    {
        return view('livewire.pages.marketing.campaigns.view-campaign');
    }
}
