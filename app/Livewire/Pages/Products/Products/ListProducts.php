<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products\Products;

use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Product;
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
final class ListProducts extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return ProductsTable::configure($table)
            ->query(Product::query())
            ->recordUrl(fn (Product $record): string => route('dashboard.products.view', ['team' => $this->team, 'product' => $record]))
            ->recordActions([
                Action::make('view')
                    ->url(fn (Product $record): string => route('dashboard.products.view', ['team' => $this->team, 'product' => $record]))
                    ->icon(Heroicon::Eye),
                Action::make('edit')
                    ->url(fn (Product $record): string => route('dashboard.products.edit', ['team' => $this->team, 'product' => $record]))
                    ->icon(Heroicon::PencilSquare),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.products.products.list-products');
    }
}
