<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CustomerType;
use App\Models\Company;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
final class CustomerFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(CustomerType::class);

        return [
            'unique_identifier' => fake()->unique()->numerify('CUST-######'),
            'name' => $type === CustomerType::Company ? fake()->company() : fake()->name(),
            'type' => $type,
            'phone' => fake()->phoneNumber(),
            'notes' => fake()->optional()->paragraph(),
            'is_active' => fake()->boolean(90),
        ];
    }

    public function b2b(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => CustomerType::Company,
            'name' => fake()->company(),
        ]);
    }

    public function b2c(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => CustomerType::Individual,
            'name' => fake()->name(),
        ]);
    }

    public function forCompany(?Company $company = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'company_id' => $company ?? Company::factory(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }
}
