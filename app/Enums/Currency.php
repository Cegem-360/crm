<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum Currency: string implements HasLabel
{
    case HUF = 'HUF';
    case EUR = 'EUR';
    case USD = 'USD';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::HUF => __('HUF - Hungarian Forint'),
            self::EUR => __('EUR - Euro'),
            self::USD => __('USD - US Dollar'),
        };
    }

    public function symbol(): string
    {
        return match ($this) {
            self::HUF => 'Ft',
            self::EUR => '€',
            self::USD => '$',
        };
    }
}
