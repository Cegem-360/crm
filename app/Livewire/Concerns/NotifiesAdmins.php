<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

trait NotifiesAdmins
{
    private function notifyAdmins(Notification $notification): void
    {
        $roleNames = [Role::Admin->value, Role::Manager->value];

        $adminsAndManagers = User::query()
            ->whereHas('roles', fn ($query) => $query->whereIn('name', $roleNames))
            ->get();

        if ($adminsAndManagers->isNotEmpty()) {
            NotificationFacade::send($adminsAndManagers, $notification);
        }
    }
}
