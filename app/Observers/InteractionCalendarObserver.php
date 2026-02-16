<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\DeleteCalendarEvent;
use App\Jobs\SyncCalendarEvent;
use App\Models\Interaction;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;

final class InteractionCalendarObserver
{
    public function created(Interaction $interaction): void
    {
        $this->syncToCalendar($interaction);
    }

    public function updated(Interaction $interaction): void
    {
        $this->syncToCalendar($interaction);
    }

    public function deleted(Interaction $interaction): void
    {
        if (blank($interaction->calendar_event_id)) {
            return;
        }

        $user = $this->getUser();
        $team = $this->getTeam();

        if (! $user || ! $team) {
            return;
        }

        dispatch(new DeleteCalendarEvent($interaction->calendar_event_id, $user, $team));
    }

    private function syncToCalendar(Interaction $interaction): void
    {
        $user = $this->getUser();
        $team = $this->getTeam();

        if (! $user || ! $team) {
            return;
        }

        dispatch(new SyncCalendarEvent($interaction, $user, $team));
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
