<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BugReportStatus;
use App\Enums\ComplaintSeverity;
use App\Models\BugReport;
use App\Models\Customer;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BugReport>
 */
final class BugReportFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'customer_id' => fake()->boolean(50) ? Customer::factory() : null,
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'severity' => fake()->randomElement(ComplaintSeverity::class),
            'status' => fake()->randomElement(BugReportStatus::class),
            'source' => fake()->randomElement(['web_form', 'crm', 'email', 'phone']),
            'screenshots' => null,
            'browser_info' => fake()->optional()->userAgent(),
            'url' => fake()->optional()->url(),
            'assigned_to' => fake()->boolean(70) ? User::factory() : null,
            'resolved_at' => fake()->boolean(30) ? fake()->dateTimeBetween('-1 month', 'now') : null,
        ];
    }

    public function forTeam(?Team $team = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'team_id' => $team ?? Team::factory(),
        ]);
    }
}
