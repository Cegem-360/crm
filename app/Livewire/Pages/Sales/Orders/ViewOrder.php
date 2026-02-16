<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Orders;

use App\Filament\Resources\Orders\Schemas\OrderInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Order;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewOrder extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Order $order;

    public function mount(Order $order): void
    {
        $this->order = $order;
    }

    public function infolist(Schema $schema): Schema
    {
        return OrderInfolist::configure($schema)
            ->record($this->order)
            ->columns(2);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.orders.view-order');
    }
}
