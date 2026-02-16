<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products\Discounts;

use App\Filament\Resources\Discounts\Schemas\DiscountInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Discount;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewDiscount extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Discount $discount;

    public function mount(Discount $discount): void
    {
        $this->discount = $discount;
    }

    public function infolist(Schema $schema): Schema
    {
        return DiscountInfolist::configure($schema)
            ->record($this->discount)
            ->columns(2);
    }

    public function render(): View
    {
        return view('livewire.pages.products.discounts.view-discount');
    }
}
