<?php

declare(strict_types=1);

use App\Enums\SupportTicketPriority;
use App\Enums\SupportTicketStatus;
use App\Filament\Resources\SupportTickets\Pages\CreateSupportTicket;
use App\Filament\Resources\SupportTickets\Pages\EditSupportTicket;
use App\Filament\Resources\SupportTickets\Pages\ListSupportTickets;
use App\Filament\Resources\SupportTickets\Pages\ViewSupportTicket;
use App\Models\SupportTicket;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();

    actingAs($this->user);

    $this->team = setUpFilamentTenant($this->user);
});

it('can list support tickets', function (): void {
    $tickets = SupportTicket::factory()
        ->count(3)
        ->forTeam($this->team)
        ->create();

    livewire(ListSupportTickets::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords($tickets);
});

it('can create a support ticket', function (): void {
    livewire(CreateSupportTicket::class)
        ->fillForm([
            'subject' => 'Test ticket subject',
            'description' => 'Test ticket description',
            'priority' => SupportTicketPriority::High,
            'status' => SupportTicketStatus::Open,
            'user_id' => $this->user->id,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(SupportTicket::class, [
        'subject' => 'Test ticket subject',
        'description' => 'Test ticket description',
        'priority' => SupportTicketPriority::High->value,
        'status' => SupportTicketStatus::Open->value,
    ]);
});

it('can view a support ticket', function (): void {
    $ticket = SupportTicket::factory()
        ->forTeam($this->team)
        ->create();

    livewire(ViewSupportTicket::class, ['record' => $ticket->id])
        ->assertSuccessful();
});

it('can edit a support ticket', function (): void {
    $ticket = SupportTicket::factory()
        ->forTeam($this->team)
        ->create([
            'user_id' => $this->user->id,
            'assigned_to' => null,
        ]);

    livewire(EditSupportTicket::class, ['record' => $ticket->id])
        ->assertFormSet([
            'subject' => $ticket->subject,
        ])
        ->fillForm([
            'subject' => 'Updated subject',
            'description' => $ticket->description,
            'priority' => $ticket->priority,
            'status' => $ticket->status,
            'user_id' => $this->user->id,
            'assigned_to' => null,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(SupportTicket::query()->find($ticket->id)->subject)->toBe('Updated subject');
});

it('generates a ticket number automatically', function (): void {
    $ticket = SupportTicket::factory()
        ->forTeam($this->team)
        ->create(['ticket_number' => null]);

    expect($ticket->ticket_number)->toStartWith('TKT-');
    expect($ticket->ticket_number)->toHaveLength(10);
});
