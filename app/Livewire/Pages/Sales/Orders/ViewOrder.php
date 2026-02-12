<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Orders;

use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewOrder extends Component
{
    use HasCurrentTeam;

    public Order $order;

    public function mount(Order $order): void
    {
        $this->order = $order->load(['customer', 'quote', 'orderItems', 'invoices']);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.orders.view-order');
    }
}
