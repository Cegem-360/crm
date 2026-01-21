<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products\Products;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewProduct extends Component
{
    public Product $product;

    public function mount(Product $product): void
    {
        $this->product = $product->load(['category']);
    }

    public function render(): View
    {
        return view('livewire.pages.products.products.view-product');
    }
}
