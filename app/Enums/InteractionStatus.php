<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum InteractionStatus: string implements HasLabel
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Failed = 'failed';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Pending => __('Pending'),
            self::Completed => __('Completed'),
            self::Failed => __('Failed'),
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Completed => 'green',
            self::Failed => 'red',
        };
    }
}
