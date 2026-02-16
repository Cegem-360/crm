<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use App\Models\LeadScore;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<LeadScore> */
final class LeadScoreFactory extends Factory
{
    protected $model = LeadScore::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'customer_id' => Customer::factory(),
            'score' => fake()->numberBetween(0, 100),
            'interaction_score' => fake()->numberBetween(0, 30),
            'recency_score' => fake()->numberBetween(0, 25),
            'opportunity_score' => fake()->numberBetween(0, 30),
            'engagement_score' => fake()->numberBetween(0, 15),
            'last_calculated_at' => now(),
        ];
    }

    public function highScore(): static
    {
        return $this->state(fn (): array => [
            'score' => fake()->numberBetween(70, 100),
            'interaction_score' => fake()->numberBetween(20, 30),
            'recency_score' => fake()->numberBetween(18, 25),
            'opportunity_score' => fake()->numberBetween(20, 30),
            'engagement_score' => fake()->numberBetween(10, 15),
        ]);
    }

    public function lowScore(): static
    {
        return $this->state(fn (): array => [
            'score' => fake()->numberBetween(0, 30),
            'interaction_score' => fake()->numberBetween(0, 10),
            'recency_score' => fake()->numberBetween(0, 8),
            'opportunity_score' => fake()->numberBetween(0, 10),
            'engagement_score' => fake()->numberBetween(0, 5),
        ]);
    }

    public function forTeam(Team $team): static
    {
        return $this->state(fn (): array => [
            'team_id' => $team->id,
        ]);
    }
}
