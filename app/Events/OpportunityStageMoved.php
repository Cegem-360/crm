<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Opportunity;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class OpportunityStageMoved
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Opportunity $opportunity,
        public readonly string $previousStage,
    ) {}
}
