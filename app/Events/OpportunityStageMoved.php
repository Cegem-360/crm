<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Opportunity;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final readonly class OpportunityStageMoved
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Opportunity $opportunity,
        public string $previousStage,
    ) {}
}
