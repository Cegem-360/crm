<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ChatMessageSenderType: string implements HasLabel
{
    case Customer = 'customer';
    case User = 'user';
    case Bot = 'bot';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Customer => __('Customer'),
            self::User => __('User'),
            self::Bot => __('Bot'),
        };
    }
}
