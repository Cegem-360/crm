<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
final class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'name' => fake()->words(3, true),
            'sku' => fake()->unique()->bothify('PRD-####-???'),
            'description' => fake()->optional()->paragraph(),
            'unit_price' => fake()->randomFloat(2, 1000, 100000),
            'tax_rate' => fake()->randomElement([0, 5, 18, 27]),
            'is_active' => fake()->boolean(95),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }

    public function forTeam(?Team $team = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'team_id' => $team ?? Team::factory(),
        ]);
    }
}
