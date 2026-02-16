<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Quotes;

use App\Filament\Resources\Quotes\Tables\QuotesTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Quote;
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
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ListQuotes extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return QuotesTable::configure($table)
            ->query(Quote::query())
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['customer', 'opportunity']))
            ->recordUrl(fn (Quote $record): string => route('dashboard.quotes.view', ['team' => $this->team, 'quote' => $record]))
            ->recordActions([
                Action::make('view')
                    ->url(fn (Quote $record): string => route('dashboard.quotes.view', ['team' => $this->team, 'quote' => $record]))
                    ->icon(Heroicon::Eye),
                Action::make('edit')
                    ->url(fn (Quote $record): string => route('dashboard.quotes.edit', ['team' => $this->team, 'quote' => $record]))
                    ->icon(Heroicon::PencilSquare),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.quotes.list-quotes');
    }
}
