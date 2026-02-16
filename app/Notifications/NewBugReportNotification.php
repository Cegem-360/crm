<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\BugReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class NewBugReportNotification extends Notification
{
    use Queueable;

    public function __construct(public BugReport $bugReport) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New Bug Report: :title', ['title' => $this->bugReport->title]))
            ->line(__('A new bug report has been submitted.'))
            ->line('**'.__('Title').':** '.$this->bugReport->title)
            ->line('**'.__('Severity').':** '.$this->bugReport->severity->getLabel())
            ->line('**'.__('Description').':**')
            ->line($this->bugReport->description)
            ->line('**'.__('Browser').':** '.($this->bugReport->browser_info ?? __('N/A')))
            ->line('**'.__('URL').':** '.($this->bugReport->url ?? __('N/A')))
            ->action(__('View Bug Report'), url('/admin/bug-reports/'.$this->bugReport->id.'/edit'))
            ->line(__('Please review and assign this bug report.'));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'bug_report_id' => $this->bugReport->id,
            'title' => $this->bugReport->title,
            'severity' => $this->bugReport->severity?->value,
        ];
    }
}
