<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products\Products;

use App\Filament\Resources\Products\Schemas\ProductInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Product;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewProduct extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Product $product;

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    public function infolist(Schema $schema): Schema
    {
        return ProductInfolist::configure($schema)
            ->record($this->product)
            ->columns(2);
    }

    public function render(): View
    {
        return view('livewire.pages.products.products.view-product');
    }
}
