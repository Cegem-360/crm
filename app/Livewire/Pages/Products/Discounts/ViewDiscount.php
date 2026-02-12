<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products\Discounts;

use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Discount;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewDiscount extends Component
{
    use HasCurrentTeam;

    public Discount $discount;

    public function mount(Discount $discount): void
    {
        $this->discount = $discount->load(['customer', 'product']);
    }

    public function render(): View
    {
        return view('livewire.pages.products.discounts.view-discount');
    }
}
