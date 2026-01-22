<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
final class CompanyFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'name' => fake()->company(),
            'tax_number' => fake()->numerify('########-#-##'),
            'registration_number' => fake()->numerify('##-##-######'),
            'email' => fake()->unique()->companyEmail(),
        ];
    }

    public function forTeam(?Team $team = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'team_id' => $team ?? Team::factory(),
        ]);
    }
}
