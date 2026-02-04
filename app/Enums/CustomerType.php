<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CustomerType: string implements HasColor, HasLabel
{
    case Individual = 'individual';
    case Company = 'company';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Individual => __('Individual'),
            self::Company => __('Company'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Individual => 'info',
            self::Company => 'primary',
        };
    }
}
