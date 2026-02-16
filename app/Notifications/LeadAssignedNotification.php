<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\LeadScore;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class LeadAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(public LeadScore $leadScore) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $customer = $this->leadScore->customer;

        return (new MailMessage)
            ->subject(__('New Lead Assigned: :name', ['name' => $customer->name]))
            ->line(__('A high-scoring lead has been assigned to you.'))
            ->line('**'.__('Customer').':** '.$customer->name)
            ->line('**'.__('Lead Score').':** '.$this->leadScore->score.'/100')
            ->line('**'.__('Interaction Score').':** '.$this->leadScore->interaction_score)
            ->line('**'.__('Opportunity Score').':** '.$this->leadScore->opportunity_score)
            ->action(__('View Customer'), url('/admin/customers/'.$customer->id.'/edit'))
            ->line(__('Please follow up with this lead promptly.'));
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'lead_score_id' => $this->leadScore->id,
            'customer_id' => $this->leadScore->customer_id,
            'customer_name' => $this->leadScore->customer->name,
            'score' => $this->leadScore->score,
        ];
    }
}
