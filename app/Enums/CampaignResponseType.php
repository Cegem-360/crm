<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CampaignResponseType: string implements HasColor, HasLabel
{
    case Interested = 'interested';
    case NotInterested = 'not_interested';
    case Callback = 'callback';
    case NoResponse = 'no_response';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Interested => __('Interested'),
            self::NotInterested => __('Not Interested'),
            self::Callback => __('Callback'),
            self::NoResponse => __('No Response'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Interested => 'success',
            self::NotInterested => 'danger',
            self::Callback => 'warning',
            self::NoResponse => 'gray',
        };
    }
}
