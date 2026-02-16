<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CommunicationChannel: string implements HasLabel
{
    case Email = 'email';
    case Sms = 'sms';
    case Chat = 'chat';
    case Social = 'social';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Email => __('Email'),
            self::Sms => __('SMS'),
            self::Chat => __('Chat'),
            self::Social => __('Social'),
        };
    }
}
