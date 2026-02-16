<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\OpportunityStageMoved;
use App\Jobs\SendWorkflowWebhook;
use App\Models\Opportunity;
use Illuminate\Support\Facades\Auth;

final class OpportunityObserver
{
    public function created(Opportunity $opportunity): void
    {
        $this->dispatchWebhooks($opportunity, 'opportunity.created');
    }

    public function updated(Opportunity $opportunity): void
    {
        if ($opportunity->wasChanged('stage')) {
            OpportunityStageMoved::dispatch($opportunity, (string) $opportunity->getOriginal('stage'));
        }

        $this->dispatchWebhooks($opportunity, 'opportunity.updated');
    }

    public function deleted(Opportunity $opportunity): void
    {
        $this->dispatchWebhooks($opportunity, 'opportunity.deleted');
    }

    private function dispatchWebhooks(Opportunity $opportunity, string $event): void
    {
        $user = Auth::user();

        if ($user === null) {
            return;
        }

        dispatch(new SendWorkflowWebhook($opportunity, $event, $user));
    }
}
