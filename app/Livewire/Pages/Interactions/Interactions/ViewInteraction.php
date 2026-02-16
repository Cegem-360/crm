<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Interactions\Interactions;

use App\Filament\Resources\Interactions\Schemas\InteractionInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Interaction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewInteraction extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Interaction $interaction;

    public function mount(Interaction $interaction): void
    {
        $this->interaction = $interaction;
    }

    public function infolist(Schema $schema): Schema
    {
        return InteractionInfolist::configure($schema)
            ->record($this->interaction)
            ->columns(2);
    }

    public function render(): View
    {
        return view('livewire.pages.interactions.interactions.view-interaction');
    }
}
