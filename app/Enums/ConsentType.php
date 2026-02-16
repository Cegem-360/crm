<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ConsentType: string implements HasColor, HasLabel
{
    case DataProcessing = 'data_processing';
    case Marketing = 'marketing';
    case Newsletter = 'newsletter';
    case Profiling = 'profiling';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::DataProcessing => __('Data Processing'),
            self::Marketing => __('Marketing'),
            self::Newsletter => __('Newsletter'),
            self::Profiling => __('Profiling'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DataProcessing => 'primary',
            self::Marketing => 'success',
            self::Newsletter => 'info',
            self::Profiling => 'warning',
        };
    }
}
