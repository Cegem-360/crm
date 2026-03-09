<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum SupportTicketStatus: string implements HasColor, HasLabel
{
    case Open = 'open';
    case InProgress = 'in_progress';
    case WaitingOnCustomer = 'waiting_on_customer';
    case Resolved = 'resolved';
    case Closed = 'closed';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Open => __('Open'),
            self::InProgress => __('In Progress'),
            self::WaitingOnCustomer => __('Waiting on Customer'),
            self::Resolved => __('Resolved'),
            self::Closed => __('Closed'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Open => 'danger',
            self::InProgress => 'warning',
            self::WaitingOnCustomer => 'info',
            self::Resolved => 'success',
            self::Closed => 'gray',
        };
    }
}
