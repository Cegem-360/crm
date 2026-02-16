<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products\ProductCategories;

use App\Filament\Resources\ProductCategories\Tables\ProductCategoriesTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\ProductCategory;
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
final class ListProductCategories extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return ProductCategoriesTable::configure($table)
            ->query(ProductCategory::query())
            ->recordUrl(fn (ProductCategory $record): string => route('dashboard.product-categories.view', ['team' => $this->team, 'productCategory' => $record]))
            ->recordActions([
                Action::make('view')
                    ->url(fn (ProductCategory $record): string => route('dashboard.product-categories.view', ['team' => $this->team, 'productCategory' => $record]))
                    ->icon(Heroicon::Eye),
                Action::make('edit')
                    ->url(fn (ProductCategory $record): string => route('dashboard.product-categories.edit', ['team' => $this->team, 'productCategory' => $record]))
                    ->icon(Heroicon::PencilSquare),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.products.product-categories.list-product-categories');
    }
}
