<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum InteractionCategory: string implements HasLabel
{
    case Sales = 'sales';
    case Marketing = 'marketing';
    case Support = 'support';
    case General = 'general';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Sales => __('Sales'),
            self::Marketing => __('Marketing'),
            self::Support => __('Support'),
            self::General => __('General'),
        };
    }
}
