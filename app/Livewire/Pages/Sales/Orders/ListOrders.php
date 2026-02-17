<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Orders;

use App\Filament\Resources\Customers\Actions\GenerateInvoiceAction;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Order;
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
final class ListOrders extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return OrdersTable::configure($table)
            ->query(Order::query())
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['customer', 'quote']))
            ->recordUrl(fn (Order $record): string => route('dashboard.orders.view', ['team' => $this->team, 'order' => $record]))
            ->recordActions([
                GenerateInvoiceAction::make(),
                Action::make('view')
                    ->url(fn (Order $record): string => route('dashboard.orders.view', ['team' => $this->team, 'order' => $record]))
                    ->icon(Heroicon::Eye),
                Action::make('edit')
                    ->url(fn (Order $record): string => route('dashboard.orders.edit', ['team' => $this->team, 'order' => $record]))
                    ->icon(Heroicon::PencilSquare),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.orders.list-orders');
    }
}
