<?php

declare(strict_types=1);

use App\Filament\Pages\CalendarSettings;
use App\Models\GoogleCalendarToken;
use App\Models\Interaction;
use App\Models\Task;
use App\Models\User;
use App\Services\GoogleCalendarService;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->team = setUpFilamentTenant($this->user);
});

it('can render calendar settings page', function (): void {
    livewire(CalendarSettings::class)
        ->assertSuccessful();
});

it('shows not connected status when no token exists', function (): void {
    livewire(CalendarSettings::class)
        ->assertSee(__('Google Calendar is not connected'));
});

it('shows connected status when token exists', function (): void {
    GoogleCalendarToken::factory()->create([
        'user_id' => $this->user->id,
        'team_id' => $this->team->id,
    ]);

    livewire(CalendarSettings::class)
        ->assertSee(__('Google Calendar is connected'));
});

it('can disconnect google calendar', function (): void {
    GoogleCalendarToken::factory()->create([
        'user_id' => $this->user->id,
        'team_id' => $this->team->id,
    ]);

    expect(GoogleCalendarToken::query()
        ->where('user_id', $this->user->id)
        ->where('team_id', $this->team->id)
        ->exists()
    )->toBeTrue();

    livewire(CalendarSettings::class)
        ->callAction('disconnect');

    expect(GoogleCalendarToken::query()
        ->where('user_id', $this->user->id)
        ->where('team_id', $this->team->id)
        ->exists()
    )->toBeFalse();
});

it('can toggle sync on and off', function (): void {
    GoogleCalendarToken::factory()->create([
        'user_id' => $this->user->id,
        'team_id' => $this->team->id,
        'sync_enabled' => true,
    ]);

    livewire(CalendarSettings::class)
        ->call('toggleSync')
        ->assertNotified(__('Calendar sync disabled'));

    expect(GoogleCalendarToken::query()
        ->where('user_id', $this->user->id)
        ->where('team_id', $this->team->id)
        ->first()
        ->sync_enabled
    )->toBeFalse();
});

it('stores calendar_event_id on task model', function (): void {
    $task = Task::factory()->for($this->team)->create([
        'calendar_event_id' => 'google-event-123',
    ]);

    expect($task->calendar_event_id)->toBe('google-event-123');
});

it('stores calendar_event_id on interaction model', function (): void {
    $interaction = Interaction::factory()->for($this->team)->create([
        'calendar_event_id' => 'google-event-456',
    ]);

    expect($interaction->calendar_event_id)->toBe('google-event-456');
});

it('detects expired token', function (): void {
    $token = GoogleCalendarToken::factory()->expired()->create([
        'user_id' => $this->user->id,
        'team_id' => $this->team->id,
    ]);

    expect($token->isExpired())->toBeTrue();
});

it('detects valid token', function (): void {
    $token = GoogleCalendarToken::factory()->create([
        'user_id' => $this->user->id,
        'team_id' => $this->team->id,
        'expires_at' => now()->addHour(),
    ]);

    expect($token->isExpired())->toBeFalse();
});

it('checks connection status via service', function (): void {
    $service = new GoogleCalendarService;

    expect($service->isConnected($this->user, $this->team))->toBeFalse();

    GoogleCalendarToken::factory()->create([
        'user_id' => $this->user->id,
        'team_id' => $this->team->id,
    ]);

    expect($service->isConnected($this->user, $this->team))->toBeTrue();
});

it('disconnects via service', function (): void {
    $service = new GoogleCalendarService;

    GoogleCalendarToken::factory()->create([
        'user_id' => $this->user->id,
        'team_id' => $this->team->id,
    ]);

    expect($service->isConnected($this->user, $this->team))->toBeTrue();

    $service->disconnect($this->user, $this->team);

    expect($service->isConnected($this->user, $this->team))->toBeFalse();
});

it('does not sync when sync is disabled', function (): void {
    $service = new GoogleCalendarService;

    GoogleCalendarToken::factory()->syncDisabled()->create([
        'user_id' => $this->user->id,
        'team_id' => $this->team->id,
    ]);

    $task = Task::factory()->for($this->team)->create();

    $result = $service->syncTaskToCalendar($task, $this->user, $this->team);

    expect($result)->toBeNull();
});

it('does not sync when no token exists', function (): void {
    $service = new GoogleCalendarService;

    $task = Task::factory()->for($this->team)->create();

    $result = $service->syncTaskToCalendar($task, $this->user, $this->team);

    expect($result)->toBeNull();
});

it('has google calendar tokens relationship on user', function (): void {
    GoogleCalendarToken::factory()->create([
        'user_id' => $this->user->id,
        'team_id' => $this->team->id,
    ]);

    expect($this->user->googleCalendarTokens)->toHaveCount(1)
        ->and($this->user->googleCalendarTokens->first())->toBeInstanceOf(GoogleCalendarToken::class);
});
