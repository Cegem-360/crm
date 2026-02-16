<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products\ProductCategories;

use App\Filament\Resources\ProductCategories\Schemas\ProductCategoryInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\ProductCategory;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewProductCategory extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ProductCategory $productCategory;

    public function mount(ProductCategory $productCategory): void
    {
        $this->productCategory = $productCategory;
    }

    public function infolist(Schema $schema): Schema
    {
        return ProductCategoryInfolist::configure($schema)
            ->record($this->productCategory)
            ->columns(2);
    }

    public function render(): View
    {
        return view('livewire.pages.products.product-categories.view-product-category');
    }
}
