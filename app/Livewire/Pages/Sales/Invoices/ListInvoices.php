<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Invoices;

use App\Filament\Resources\Invoices\Tables\InvoicesTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Invoice;
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
final class ListInvoices extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return InvoicesTable::configure($table)
            ->query(Invoice::query())
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['customer', 'order']))
            ->recordUrl(fn (Invoice $record): string => route('dashboard.invoices.view', ['team' => $this->team, 'invoice' => $record]))
            ->recordActions([
                Action::make('view')
                    ->url(fn (Invoice $record): string => route('dashboard.invoices.view', ['team' => $this->team, 'invoice' => $record]))
                    ->icon(Heroicon::Eye),
                Action::make('edit')
                    ->url(fn (Invoice $record): string => route('dashboard.invoices.edit', ['team' => $this->team, 'invoice' => $record]))
                    ->icon(Heroicon::PencilSquare),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.invoices.list-invoices');
    }
}
