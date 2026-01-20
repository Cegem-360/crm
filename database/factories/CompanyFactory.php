<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
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
            'name' => fake()->company(),
            'tax_number' => fake()->numerify('########-#-##'),
            'registration_number' => fake()->numerify('##-##-######'),
            'email' => fake()->unique()->companyEmail(),
        ];
    }
}
