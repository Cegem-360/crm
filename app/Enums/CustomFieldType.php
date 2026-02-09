<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CustomFieldType: string implements HasColor, HasLabel
{
    case Text = 'text';
    case Number = 'number';
    case Date = 'date';
    case Checkbox = 'checkbox';
    case Select = 'select';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Text => __('Text'),
            self::Number => __('Number'),
            self::Date => __('Date'),
            self::Checkbox => __('Checkbox'),
            self::Select => __('Select / Dropdown'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Text => 'gray',
            self::Number => 'info',
            self::Date => 'warning',
            self::Checkbox => 'success',
            self::Select => 'primary',
        };
    }
}
