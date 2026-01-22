<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
final class TaskFactory extends Factory
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
            'customer_id' => Customer::factory(),
            'assigned_to' => User::factory(),
            'assigned_by' => User::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed', 'cancelled']),
            'due_date' => fake()->dateTimeBetween('now', '+30 days'),
            'completed_at' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'pending',
            'completed_at' => null,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'in_progress',
            'completed_at' => null,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function urgent(): static
    {
        return $this->state(fn (array $attributes): array => [
            'priority' => 'urgent',
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes): array => [
            'due_date' => fake()->dateTimeBetween('-1 month', '-1 day'),
            'status' => 'pending',
            'completed_at' => null,
        ]);
    }
}
