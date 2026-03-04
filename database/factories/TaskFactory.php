<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
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
            'priority' => fake()->randomElement(TaskPriority::cases()),
            'status' => fake()->randomElement(TaskStatus::cases()),
            'due_date' => fake()->dateTimeBetween('now', '+30 days'),
            'completed_at' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => TaskStatus::Pending,
            'completed_at' => null,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => TaskStatus::InProgress,
            'completed_at' => null,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => TaskStatus::Completed,
            'completed_at' => now(),
        ]);
    }

    public function urgent(): static
    {
        return $this->state(fn (array $attributes): array => [
            'priority' => TaskPriority::Urgent,
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes): array => [
            'due_date' => fake()->dateTimeBetween('-1 month', '-1 day'),
            'status' => TaskStatus::Pending,
            'completed_at' => null,
        ]);
    }
}
