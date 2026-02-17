<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\QuoteStatusChanged;
use App\Jobs\SendWorkflowWebhook;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;

final class QuoteObserver
{
    public function created(Quote $quote): void
    {
        $this->dispatchWebhooks($quote, 'quote.created');
    }

    public function updated(Quote $quote): void
    {
        if ($quote->wasChanged('status')) {
            event(new QuoteStatusChanged($quote, (string) $quote->getRawOriginal('status')));
        }

        $this->dispatchWebhooks($quote, 'quote.updated');
    }

    public function deleted(Quote $quote): void
    {
        $this->dispatchWebhooks($quote, 'quote.deleted');
    }

    private function dispatchWebhooks(Quote $quote, string $event): void
    {
        $user = Auth::user();

        if ($user === null) {
            return;
        }

        dispatch(new SendWorkflowWebhook($quote, $event, $user));
    }
}
