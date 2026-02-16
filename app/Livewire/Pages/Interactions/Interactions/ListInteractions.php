<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Interactions\Interactions;

use App\Filament\Resources\Interactions\Tables\InteractionsTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Interaction;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ListInteractions extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return InteractionsTable::configure($table)
            ->query(Interaction::query())
            ->recordUrl(fn (Interaction $record): string => route('dashboard.interactions.view', ['team' => $this->team, 'interaction' => $record]))
            ->recordActions([
                Action::make('view')
                    ->url(fn (Interaction $record): string => route('dashboard.interactions.view', ['team' => $this->team, 'interaction' => $record]))
                    ->icon(Heroicon::Eye),
                Action::make('edit')
                    ->url(fn (Interaction $record): string => route('dashboard.interactions.edit', ['team' => $this->team, 'interaction' => $record]))
                    ->icon(Heroicon::PencilSquare),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.interactions.interactions.list-interactions');
    }
}
