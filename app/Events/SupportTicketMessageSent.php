<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\SupportTicketMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class SupportTicketMessageSent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public SupportTicketMessage $message) {}

    /**
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('support.ticket.'.$this->message->support_ticket_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ticket.message.sent';
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'body' => $this->message->body,
            'user_id' => $this->message->user_id,
            'user_name' => $this->message->user?->name,
            'is_internal_note' => $this->message->is_internal_note,
            'created_at' => $this->message->created_at?->toISOString(),
        ];
    }
}
