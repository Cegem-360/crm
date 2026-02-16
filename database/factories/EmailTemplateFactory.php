<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EmailTemplateCategory;
use App\Models\EmailTemplate;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<EmailTemplate> */
final class EmailTemplateFactory extends Factory
{
    protected $model = EmailTemplate::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'name' => fake()->words(3, true),
            'subject' => fake()->sentence(),
            'body' => '<p>'.fake()->paragraph().'</p>',
            'category' => fake()->randomElement(EmailTemplateCategory::class),
            'variables' => ['customer_name', 'contact_name', 'user_name'],
            'is_active' => true,
            'created_by' => User::factory(),
        ];
    }

    public function sales(): static
    {
        return $this->state(fn (): array => [
            'category' => EmailTemplateCategory::Sales,
        ]);
    }

    public function marketing(): static
    {
        return $this->state(fn (): array => [
            'category' => EmailTemplateCategory::Marketing,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'is_active' => false,
        ]);
    }

    public function forTeam(Team $team): static
    {
        return $this->state(fn (): array => [
            'team_id' => $team->id,
        ]);
    }
}
