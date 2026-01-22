<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Orders;

use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Order;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditOrder extends Component implements HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithSchemas;

    public ?Order $order = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?Order $order = null): void
    {
        $this->order = $order;

        $this->form->fill($order?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema)
            ->statePath('data')
            ->model($this->order ?? Order::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->order?->exists) {
            $this->order->update($data);
            $message = __('Order updated successfully.');
        } else {
            $this->order = Order::create(array_merge($data, ['team_id' => $this->team->id]));
            $this->form->model($this->order)->saveRelationships();
            $message = __('Order created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.orders.view', ['team' => $this->team, 'order' => $this->order]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.orders.edit-order');
    }
}
