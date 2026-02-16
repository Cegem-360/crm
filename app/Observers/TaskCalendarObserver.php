<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\DeleteCalendarEvent;
use App\Jobs\SyncCalendarEvent;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;

final class TaskCalendarObserver
{
    public function created(Task $task): void
    {
        $this->syncToCalendar($task);
    }

    public function updated(Task $task): void
    {
        $this->syncToCalendar($task);
    }

    public function deleted(Task $task): void
    {
        if (blank($task->calendar_event_id)) {
            return;
        }

        $user = $this->getUser();
        $team = $this->getTeam();

        if (! $user || ! $team) {
            return;
        }

        dispatch(new DeleteCalendarEvent($task->calendar_event_id, $user, $team));
    }

    private function syncToCalendar(Task $task): void
    {
        $user = $this->getUser();
        $team = $this->getTeam();

        if (! $user || ! $team) {
            return;
        }

        dispatch(new SyncCalendarEvent($task, $user, $team));
    }

    private function getUser(): ?User
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user;
    }

    private function getTeam(): ?Team
    {
        /** @var Team|null $tenant */
        $tenant = Filament::getTenant();

        return $tenant;
    }
}
