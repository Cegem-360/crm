<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\SendProductWebhook;
use App\Jobs\SendWorkflowWebhook;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

final class ProductObserver
{
    public function created(Product $product): void
    {
        $this->dispatchWebhooks($product, 'created');
    }

    public function updated(Product $product): void
    {
        $this->dispatchWebhooks($product, 'updated');
    }

    public function deleted(Product $product): void
    {
        $this->dispatchWebhooks($product, 'deleted');
    }

    public function restored(Product $product): void
    {
        $this->dispatchWebhooks($product, 'restored');
    }

    public function forceDeleted(Product $product): void
    {
        $this->dispatchWebhooks($product, 'force_deleted');
    }

    private function dispatchWebhooks(Product $product, string $event): void
    {
        $user = Auth::user();

        if ($user === null) {
            return;
        }

        // Workflow webhook - user token-nel
        SendWorkflowWebhook::dispatch($product, $event, $user);

        // Felhasználó-specifikus webhook
        SendProductWebhook::dispatch($product, $event, $user);
    }
}
