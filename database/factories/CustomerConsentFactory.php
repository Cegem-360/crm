<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ConsentType;
use App\Models\Customer;
use App\Models\CustomerConsent;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerConsent>
 */
final class CustomerConsentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isGranted = fake()->boolean(70);

        return [
            'team_id' => Team::factory(),
            'customer_id' => Customer::factory(),
            'granted_by' => User::factory(),
            'type' => fake()->randomElement(ConsentType::class),
            'is_granted' => $isGranted,
            'granted_at' => $isGranted ? fake()->dateTimeBetween('-1 year') : null,
            'revoked_at' => $isGranted ? null : fake()->dateTimeBetween('-6 months'),
            'ip_address' => fake()->optional()->ipv4(),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function granted(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_granted' => true,
            'granted_at' => now(),
            'revoked_at' => null,
        ]);
    }

    public function revoked(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_granted' => false,
            'granted_at' => fake()->dateTimeBetween('-1 year', '-1 month'),
            'revoked_at' => now(),
        ]);
    }

    public function forTeam(?Team $team = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'team_id' => $team ?? Team::factory(),
        ]);
    }
}
