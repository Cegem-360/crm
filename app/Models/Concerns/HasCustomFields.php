<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\CustomFieldType;
use App\Models\CustomField;
use App\Models\CustomFieldValue;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

trait HasCustomFields
{
    /**
     * Get all custom fields applicable to this model type.
     *
     * @return Collection<int, CustomField>
     */
    public static function getCustomFields(bool $activeOnly = true): Collection
    {
        $morphAlias = array_search(static::class, Relation::morphMap(), true);

        $query = CustomField::query()
            ->where('model_type', $morphAlias)
            ->orderBy('sort_order');

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        return $query->get();
    }

    /**
     * @return MorphMany<CustomFieldValue, $this>
     */
    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'model');
    }

    /**
     * Get the value for a specific custom field.
     */
    public function getCustomFieldValue(string $slug): mixed
    {
        $value = $this->customFieldValues
            ->first(fn (CustomFieldValue $value): bool => $value->customField->slug === $slug);

        return $value?->getTypedValue();
    }

    /**
     * Set the value for a specific custom field.
     */
    public function setCustomFieldValue(string $slug, mixed $value): void
    {
        $morphAlias = array_search(static::class, Relation::morphMap(), true);

        $customField = CustomField::query()
            ->where('slug', $slug)
            ->where('model_type', $morphAlias)
            ->firstOrFail();

        $this->customFieldValues()->updateOrCreate(
            ['custom_field_id' => $customField->id],
            ['value' => $this->serializeCustomFieldValue($customField, $value)],
        );
    }

    /**
     * Save multiple custom field values at once.
     *
     * @param  array<string, mixed>  $values  Key is the custom field slug
     */
    public function saveCustomFieldValues(array $values): void
    {
        foreach ($values as $slug => $value) {
            $this->setCustomFieldValue($slug, $value);
        }
    }

    /**
     * Serialize the value for storage.
     */
    protected function serializeCustomFieldValue(CustomField $customField, mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return match ($customField->type) {
            CustomFieldType::Checkbox => $value ? '1' : '0',
            CustomFieldType::Date => $value instanceof DateTimeInterface
                ? $value->format('Y-m-d')
                : $value,
            default => (string) $value,
        };
    }
}
