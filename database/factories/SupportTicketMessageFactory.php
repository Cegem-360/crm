<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupportTicketMessage>
 */
final class SupportTicketMessageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'support_ticket_id' => SupportTicket::factory(),
            'user_id' => User::factory(),
            'body' => fake()->paragraphs(fake()->numberBetween(1, 3), true),
            'is_internal_note' => fake()->boolean(20),
            'is_read' => fake()->boolean(50),
            'read_at' => fake()->boolean(50) ? fake()->dateTimeBetween('-1 week', 'now') : null,
        ];
    }

    public function internalNote(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_internal_note' => true,
        ]);
    }
}
