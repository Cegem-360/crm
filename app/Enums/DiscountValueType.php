<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum DiscountValueType: string implements HasLabel
{
    case Fixed = 'fixed';
    case Percentage = 'percentage';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Fixed => __('Fixed'),
            self::Percentage => __('Percentage'),
        };
    }
}
