<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Task;
use App\Notifications\TaskAssignedNotification;

final class TaskNotificationObserver
{
    public function created(Task $task): void
    {
        $this->notifyAssignee($task);
    }

    public function updated(Task $task): void
    {
        if ($task->wasChanged('assigned_to') && $task->assigned_to) {
            $this->notifyAssignee($task);
        }
    }

    private function notifyAssignee(Task $task): void
    {
        $assignedUser = $task->assignedUser;

        if (! $assignedUser) {
            return;
        }

        $assignedUser->notify(new TaskAssignedNotification($task));
    }
}
