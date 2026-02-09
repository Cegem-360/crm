<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CustomFieldModel;
use App\Enums\CustomFieldType;
use App\Models\CustomField;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomField>
 */
final class CustomFieldFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'slug' => fake()->unique()->slug(2),
            'type' => fake()->randomElement(CustomFieldType::cases()),
            'model_type' => fake()->randomElement(CustomFieldModel::cases()),
            'options' => null,
            'description' => fake()->optional()->sentence(),
            'sort_order' => fake()->numberBetween(0, 100),
            'is_active' => true,
            'is_visible_in_form' => true,
            'is_visible_in_table' => fake()->boolean(30),
            'is_visible_in_infolist' => true,
        ];
    }

    public function forCustomer(): static
    {
        return $this->state(fn (array $attributes): array => [
            'model_type' => CustomFieldModel::Customer,
        ]);
    }

    public function forOrder(): static
    {
        return $this->state(fn (array $attributes): array => [
            'model_type' => CustomFieldModel::Order,
        ]);
    }

    public function forQuote(): static
    {
        return $this->state(fn (array $attributes): array => [
            'model_type' => CustomFieldModel::Quote,
        ]);
    }

    public function forInvoice(): static
    {
        return $this->state(fn (array $attributes): array => [
            'model_type' => CustomFieldModel::Invoice,
        ]);
    }

    public function forOpportunity(): static
    {
        return $this->state(fn (array $attributes): array => [
            'model_type' => CustomFieldModel::Opportunity,
        ]);
    }

    public function forTask(): static
    {
        return $this->state(fn (array $attributes): array => [
            'model_type' => CustomFieldModel::Task,
        ]);
    }

    public function forComplaint(): static
    {
        return $this->state(fn (array $attributes): array => [
            'model_type' => CustomFieldModel::Complaint,
        ]);
    }

    public function forProduct(): static
    {
        return $this->state(fn (array $attributes): array => [
            'model_type' => CustomFieldModel::Product,
        ]);
    }

    public function text(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => CustomFieldType::Text,
        ]);
    }

    public function number(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => CustomFieldType::Number,
        ]);
    }

    public function date(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => CustomFieldType::Date,
        ]);
    }

    public function checkbox(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => CustomFieldType::Checkbox,
        ]);
    }

    public function select(array $options = ['Option A', 'Option B', 'Option C']): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => CustomFieldType::Select,
            'options' => $options,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }

    public function hiddenInForm(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_visible_in_form' => false,
        ]);
    }

    public function visibleInTable(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_visible_in_table' => true,
        ]);
    }
}
