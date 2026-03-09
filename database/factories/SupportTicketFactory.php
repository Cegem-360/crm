<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SupportTicketCategory;
use App\Enums\SupportTicketPriority;
use App\Enums\SupportTicketStatus;
use App\Models\SupportTicket;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupportTicket>
 */
final class SupportTicketFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
            'assigned_to' => fake()->boolean(60) ? User::factory() : null,
            'ticket_number' => 'TKT-'.mb_str_pad((string) fake()->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'subject' => fake()->sentence(),
            'description' => fake()->paragraphs(2, true),
            'category' => fake()->randomElement(SupportTicketCategory::class),
            'priority' => fake()->randomElement(SupportTicketPriority::class),
            'status' => fake()->randomElement(SupportTicketStatus::class),
            'resolved_at' => fake()->boolean(20) ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'closed_at' => fake()->boolean(10) ? fake()->dateTimeBetween('-1 month', 'now') : null,
        ];
    }

    public function forTeam(?Team $team = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'team_id' => $team ?? Team::factory(),
        ]);
    }
}
