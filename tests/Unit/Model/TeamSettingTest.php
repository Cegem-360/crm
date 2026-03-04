<?php

declare(strict_types=1);

use App\Enums\Currency;
use App\Models\Team;
use App\Models\TeamSetting;

it('can be created with factory', function (): void {
    $setting = TeamSetting::factory()->create();

    expect($setting)->toBeInstanceOf(TeamSetting::class)
        ->and($setting->currency)->toBe(Currency::HUF);
});

it('casts `currency` to `Currency` enum', function (): void {
    $setting = TeamSetting::factory()->create(['currency' => 'EUR']);

    expect($setting->currency)->toBe(Currency::EUR)
        ->and($setting->currency)->toBeInstanceOf(Currency::class);
});

it('belongs to a team', function (): void {
    $team = Team::factory()->create();
    $setting = TeamSetting::factory()->create(['team_id' => $team->id]);

    expect($setting->team->id)->toBe($team->id);
});

it('returns `HUF` as default from `currentCurrency()` when no team is bound', function (): void {
    app()->forgetInstance(Team::CONTAINER_BINDING);

    expect(TeamSetting::currentCurrency())->toBe('HUF');
});

it('returns `HUF` from `currentCurrency()` when team has no setting', function (): void {
    $team = Team::factory()->create();
    app()->instance(Team::CONTAINER_BINDING, $team);

    expect(TeamSetting::currentCurrency())->toBe('HUF');
});

it('returns configured currency from `currentCurrency()`', function (): void {
    $team = Team::factory()->create();
    app()->instance(Team::CONTAINER_BINDING, $team);

    TeamSetting::factory()->create([
        'team_id' => $team->id,
        'currency' => Currency::EUR,
    ]);

    expect(TeamSetting::currentCurrency())->toBe('EUR');
});

it('returns different currencies for different teams', function (): void {
    $teamA = Team::factory()->create();
    $teamB = Team::factory()->create();

    TeamSetting::factory()->create(['team_id' => $teamA->id, 'currency' => Currency::USD]);
    TeamSetting::factory()->create(['team_id' => $teamB->id, 'currency' => Currency::EUR]);

    app()->instance(Team::CONTAINER_BINDING, $teamA);
    expect(TeamSetting::currentCurrency())->toBe('USD');

    app()->instance(Team::CONTAINER_BINDING, $teamB);
    expect(TeamSetting::currentCurrency())->toBe('EUR');
});
