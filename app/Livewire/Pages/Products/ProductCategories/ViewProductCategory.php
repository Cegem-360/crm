<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products\ProductCategories;

use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\ProductCategory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewProductCategory extends Component
{
    use HasCurrentTeam;

    public ProductCategory $productCategory;

    public function mount(ProductCategory $productCategory): void
    {
        $this->productCategory = $productCategory->load(['parent', 'children', 'products']);
    }

    public function render(): View
    {
        return view('livewire.pages.products.product-categories.view-product-category');
    }
}
