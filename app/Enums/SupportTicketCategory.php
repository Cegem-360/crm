<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum SupportTicketCategory: string implements HasLabel
{
    case General = 'general';
    case Technical = 'technical';
    case Billing = 'billing';
    case FeatureRequest = 'feature_request';
    case Other = 'other';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::General => __('General'),
            self::Technical => __('Technical'),
            self::Billing => __('Billing'),
            self::FeatureRequest => __('Feature Request'),
            self::Other => __('Other'),
        };
    }
}
