<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Interaction;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use App\Services\GoogleCalendarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class SyncCalendarEvent implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(
        public Model $model,
        public User $user,
        public Team $team,
    ) {}

    public function handle(GoogleCalendarService $calendarService): void
    {
        $eventId = match (true) {
            $this->model instanceof Task => $calendarService->syncTaskToCalendar($this->model, $this->user, $this->team),
            $this->model instanceof Interaction => $calendarService->syncInteractionToCalendar($this->model, $this->user, $this->team),
            default => null,
        };

        if ($eventId !== null && $this->model->calendar_event_id !== $eventId) {
            $this->model->updateQuietly(['calendar_event_id' => $eventId]);
        }
    }
}
