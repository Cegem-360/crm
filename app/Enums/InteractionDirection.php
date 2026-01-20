<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum InteractionDirection: string implements HasLabel
{
    case Inbound = 'inbound';
    case Outbound = 'outbound';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Inbound => __('Inbound'),
            self::Outbound => __('Outbound'),
        };
    }
}
