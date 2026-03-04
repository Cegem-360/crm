<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum NavigationGroup: string implements HasLabel
{
    case Customers = 'Customers';
    case Sales = 'Sales';
    case Products = 'Products';
    case Marketing = 'Marketing';
    case Activities = 'Activities';
    case Support = 'Support';
    case Reports = 'Reports';
    case Settings = 'Settings';
    case System = 'System';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Customers => __('Customers'),
            self::Sales => __('Sales'),
            self::Support => __('Support'),
            self::Products => __('Products'),
            self::Activities => __('Activities'),
            self::Marketing => __('Marketing'),
            self::Reports => __('Reports'),
            self::Settings => __('Settings'),
            self::System => __('System'),
        };
    }

    public function getSort(): int
    {
        return match ($this) {
            self::Customers => 10,
            self::Sales => 20,
            self::Products => 30,
            self::Marketing => 40,
            self::Activities => 50,
            self::Support => 60,
            self::Reports => 70,
            self::Settings => 80,
            self::System => 90,
        };
    }
}
