<?php

declare(strict_types=1);

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum NavigationGroup: string implements HasIcon, HasLabel
{
    case Customers = 'Customers';
    case Sales = 'Sales';
    case Support = 'Support';
    case Products = 'Products';
    case Interactions = 'Interactions';
    case Marketing = 'Marketing';
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
            self::Interactions => __('Interactions'),
            self::Marketing => __('Marketing'),
            self::Reports => __('Reports'),
            self::Settings => __('Settings'),
            self::System => __('System'),
        };
    }

    public function getIcon(): string|BackedEnum|null
    {
        return match ($this) {
            self::Customers => 'heroicon-o-user-group',
            self::Sales => 'heroicon-o-currency-dollar',
            self::Support => 'heroicon-o-lifebuoy',
            self::Products => 'heroicon-o-cube',
            self::Interactions => 'heroicon-o-chat-bubble-left-ellipsis',
            self::Marketing => 'heroicon-o-megaphone',
            self::Reports => 'heroicon-o-chart-bar',
            self::Settings => 'heroicon-o-cog-6-tooth',
            self::System => 'heroicon-o-server',
        };
    }

    public function getSort(): int
    {
        return match ($this) {
            self::Customers => 10,
            self::Sales => 20,
            self::Support => 30,
            self::Products => 40,
            self::Interactions => 50,
            self::Marketing => 60,
            self::Reports => 70,
            self::Settings => 80,
            self::System => 90,
        };
    }
}
