<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\GoogleCalendarToken;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<GoogleCalendarToken> */
final class GoogleCalendarTokenFactory extends Factory
{
    protected $model = GoogleCalendarToken::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'team_id' => Team::factory(),
            'access_token' => $this->faker->sha256(),
            'refresh_token' => $this->faker->sha256(),
            'expires_at' => now()->addHour(),
            'calendar_id' => 'primary',
            'sync_enabled' => true,
        ];
    }

    public function expired(): static
    {
        return $this->state([
            'expires_at' => now()->subHour(),
        ]);
    }

    public function syncDisabled(): static
    {
        return $this->state([
            'sync_enabled' => false,
        ]);
    }

    public function forTeam(Team $team): static
    {
        return $this->state([
            'team_id' => $team->id,
        ]);
    }
}
