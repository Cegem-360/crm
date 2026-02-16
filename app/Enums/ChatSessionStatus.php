<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ChatSessionStatus: string implements HasColor, HasLabel
{
    case Active = 'active';
    case Closed = 'closed';
    case Transferred = 'transferred';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Active => __('Active'),
            self::Closed => __('Closed'),
            self::Transferred => __('Transferred'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Active => 'success',
            self::Closed => 'gray',
            self::Transferred => 'info',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Active => 'green',
            self::Closed => 'gray',
            self::Transferred => 'blue',
        };
    }
}
