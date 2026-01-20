<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum InteractionChannel: string implements HasLabel
{
    case Email = 'email';
    case Phone = 'phone';
    case Sms = 'sms';
    case Chat = 'chat';
    case Social = 'social';
    case InPerson = 'in_person';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Email => __('Email'),
            self::Phone => __('Phone'),
            self::Sms => __('SMS'),
            self::Chat => __('Chat'),
            self::Social => __('Social Media'),
            self::InPerson => __('In Person'),
        };
    }
}
