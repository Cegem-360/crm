<?php

declare(strict_types=1);

namespace App\Enums;

enum ChatSessionStatus: string
{
    case Active = 'active';
    case Closed = 'closed';
    case Transferred = 'transferred';

    public function badgeColor(): string
    {
        return match ($this) {
            self::Active => 'green',
            self::Closed => 'gray',
            self::Transferred => 'blue',
        };
    }
}
