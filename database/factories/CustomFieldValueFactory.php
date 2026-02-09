<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomField;
use App\Models\CustomFieldValue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomFieldValue>
 */
final class CustomFieldValueFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'custom_field_id' => CustomField::factory(),
            'model_type' => 'customer',
            'model_id' => Customer::factory(),
            'value' => fake()->word(),
        ];
    }

    public function forCustomField(CustomField $customField): static
    {
        return $this->state(fn (array $attributes): array => [
            'custom_field_id' => $customField->id,
        ]);
    }

    public function forModel(string $modelType, int $modelId): static
    {
        return $this->state(fn (array $attributes): array => [
            'model_type' => $modelType,
            'model_id' => $modelId,
        ]);
    }
}
