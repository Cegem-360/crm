<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class TaskAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(public Task $task) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $assigner = $this->task->assigner;

        return (new MailMessage)
            ->subject(__('New Task Assigned: :title', ['title' => $this->task->title]))
            ->line(__('A new task has been assigned to you.'))
            ->line('**'.__('Task').':** '.$this->task->title)
            ->line('**'.__('Priority').':** '.ucfirst($this->task->priority))
            ->when($this->task->due_date, fn (MailMessage $message) => $message->line('**'.__('Due Date').':** '.$this->task->due_date->format('Y-m-d')))
            ->when($assigner, fn (MailMessage $message) => $message->line('**'.__('Assigned By').':** '.$assigner->name))
            ->when($this->task->description, fn (MailMessage $message) => $message->line('**'.__('Description').':** '.$this->task->description))
            ->line(__('Please review and complete this task.'));
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'priority' => $this->task->priority,
            'assigned_by' => $this->task->assigner?->name,
        ];
    }
}
