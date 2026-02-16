<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Jobs\SendWorkflowWebhook;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

final class OrderObserver
{
    public function created(Order $order): void
    {
        OrderCreated::dispatch($order);
        $this->dispatchWebhooks($order, 'order.created');
    }

    public function updated(Order $order): void
    {
        if ($order->wasChanged('status')) {
            OrderStatusChanged::dispatch($order, (string) $order->getOriginal('status'));
        }

        $this->dispatchWebhooks($order, 'order.updated');
    }

    public function deleted(Order $order): void
    {
        $this->dispatchWebhooks($order, 'order.deleted');
    }

    private function dispatchWebhooks(Order $order, string $event): void
    {
        $user = Auth::user();

        if ($user === null) {
            return;
        }

        dispatch(new SendWorkflowWebhook($order, $event, $user));
    }
}
