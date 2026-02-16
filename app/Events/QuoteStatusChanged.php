<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Quote;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class QuoteStatusChanged
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Quote $quote,
        public readonly string $previousStatus,
    ) {}
}
