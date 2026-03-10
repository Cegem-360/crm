<?php

declare(strict_types=1);

use App\Enums\Currency;
use App\Filament\Pages\ManageTeamSettings;
use App\Models\TeamSetting;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->team = setUpFilamentTenant($this->user);
});

it('can render the settings page', function (): void {
    livewire(ManageTeamSettings::class)
        ->assertSuccessful();
});

it('shows default currency when no setting exists', function (): void {
    livewire(ManageTeamSettings::class)
        ->assertSchemaStateSet([
            'currency' => Currency::HUF,
        ]);
});

it('loads existing team setting', function (): void {
    TeamSetting::factory()->create([
        'team_id' => $this->team->id,
        'currency' => Currency::EUR,
    ]);

    livewire(ManageTeamSettings::class)
        ->assertSchemaStateSet([
            'currency' => Currency::EUR,
        ]);
});

it('can save currency setting', function (): void {
    livewire(ManageTeamSettings::class)
        ->fillForm(['currency' => Currency::USD->value])
        ->call('save')
        ->assertNotified();

    expect(TeamSetting::query()->where('team_id', $this->team->id)->first())
        ->currency->toBe(Currency::USD);
});

it('can update existing currency setting', function (): void {
    TeamSetting::factory()->create([
        'team_id' => $this->team->id,
        'currency' => Currency::HUF,
    ]);

    livewire(ManageTeamSettings::class)
        ->fillForm(['currency' => Currency::EUR->value])
        ->call('save')
        ->assertNotified();

    expect(TeamSetting::query()->where('team_id', $this->team->id)->first())
        ->currency->toBe(Currency::EUR);
});

it('validates that currency is required', function (): void {
    livewire(ManageTeamSettings::class)
        ->fillForm(['currency' => null])
        ->call('save')
        ->assertHasFormErrors(['currency' => 'required']);
});

it('can save Gemini API key', function (): void {
    livewire(ManageTeamSettings::class)
        ->fillForm([
            'currency' => Currency::HUF->value,
            'gemini_api_key' => 'test-gemini-key-123',
        ])
        ->call('save')
        ->assertNotified();

    $setting = TeamSetting::query()->where('team_id', $this->team->id)->first();

    expect($setting)
        ->gemini_api_key->toBe('test-gemini-key-123');
});

it('can save monthly token limit', function (): void {
    livewire(ManageTeamSettings::class)
        ->fillForm([
            'currency' => Currency::HUF->value,
            'ai_monthly_token_limit' => 50000,
        ])
        ->call('save')
        ->assertNotified();

    $setting = TeamSetting::query()->where('team_id', $this->team->id)->first();

    expect($setting)
        ->ai_monthly_token_limit->toBe(50000);
});

it('loads existing AI settings', function (): void {
    TeamSetting::factory()->create([
        'team_id' => $this->team->id,
        'gemini_api_key' => 'existing-key',
        'ai_monthly_token_limit' => 75000,
    ]);

    livewire(ManageTeamSettings::class)
        ->assertSchemaStateSet([
            'gemini_api_key' => 'existing-key',
            'ai_monthly_token_limit' => 75000,
        ]);
});
