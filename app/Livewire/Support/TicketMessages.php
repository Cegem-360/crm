<?php

declare(strict_types=1);

namespace App\Livewire\Support;

use App\Events\SupportTicketMessageSent;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

final class TicketMessages extends Component
{
    public SupportTicket $ticket;

    #[Validate('required|string|max:5000')]
    public string $body = '';

    public bool $isInternalNote = false;

    public function mount(SupportTicket $ticket): void
    {
        $this->ticket = $ticket;
    }

    public function sendMessage(): void
    {
        $this->validate();

        if (in_array(mb_trim($this->body), ['', '0'], true)) {
            return;
        }

        $message = SupportTicketMessage::query()->create([
            'support_ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'body' => $this->body,
            'is_internal_note' => $this->isInternalNote,
        ]);

        event(new SupportTicketMessageSent($message));

        $this->body = '';
        $this->isInternalNote = false;

        $this->dispatch('message-sent');
        $this->dispatch('scroll-to-bottom');
    }

    #[On('echo-private:support.ticket.{ticket.id},.ticket.message.sent')]
    public function handleNewMessage(): void
    {
        $this->refreshMessages();
    }

    public function refreshMessages(): void
    {
        $this->ticket->refresh();
        $this->ticket->load(['messages.user']);
        $this->dispatch('scroll-to-bottom');
    }

    public function render(): Factory|View
    {
        return view('livewire.support.ticket-messages');
    }
}
