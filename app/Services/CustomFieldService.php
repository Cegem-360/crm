<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CustomFieldModel;
use App\Enums\CustomFieldType;
use App\Models\CustomField;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class CustomFieldService
{
    private const int CACHE_TTL = 3600;

    /**
     * Get cached custom fields for a model type.
     *
     * @return Collection<int, CustomField>
     */
    public function getFieldsForModel(CustomFieldModel $modelType): Collection
    {
        $cacheKey = 'custom_fields.'.$modelType->value;

        return Cache::remember(
            $cacheKey,
            self::CACHE_TTL,
            fn (): Collection => CustomField::query()
                ->where('model_type', $modelType)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        );
    }

    /**
     * Clear the cache for a specific model type or all model types.
     */
    public function clearCache(?CustomFieldModel $modelType = null): void
    {
        if ($modelType instanceof CustomFieldModel) {
            Cache::forget('custom_fields.'.$modelType->value);
        } else {
            foreach (CustomFieldModel::cases() as $type) {
                Cache::forget('custom_fields.'.$type->value);
            }
        }
    }

    /**
     * Generate Filament form components for custom fields.
     *
     * @return array<Component>
     */
    public function getFormComponents(CustomFieldModel $modelType): array
    {
        return $this->getFieldsForModel($modelType)
            ->filter(fn (CustomField $field): bool => $field->is_visible_in_form)
            ->map(fn (CustomField $field): Component => $this->makeFormComponent($field))
            ->all();
    }

    /**
     * Generate Filament table columns for custom fields.
     *
     * @return array<Column>
     */
    public function getTableColumns(CustomFieldModel $modelType): array
    {
        return $this->getFieldsForModel($modelType)
            ->filter(fn (CustomField $field): bool => $field->is_visible_in_table)
            ->map(fn (CustomField $field): Column => $this->makeTableColumn($field))
            ->all();
    }

    /**
     * Generate Filament infolist entries for custom fields.
     *
     * @return array<Entry>
     */
    public function getInfolistEntries(CustomFieldModel $modelType): array
    {
        return $this->getFieldsForModel($modelType)
            ->filter(fn (CustomField $field): bool => $field->is_visible_in_infolist)
            ->map(fn (CustomField $field): Entry => $this->makeInfolistEntry($field))
            ->all();
    }

    /**
     * Prepare custom field data for form filling.
     *
     * @return array<string, mixed>
     */
    public function prepareFormData(Model $record): array
    {
        if (! method_exists($record, 'customFieldValues')) {
            return [];
        }

        $data = [];

        $record->loadMissing('customFieldValues.customField');

        foreach ($record->customFieldValues as $value) {
            $data['custom_fields.'.$value->customField->slug] = $value->getTypedValue();
        }

        return $data;
    }

    /**
     * Save custom field values from form data.
     *
     * @param  array<string, mixed>  $data
     */
    public function saveFormData(Model $record, array $data): void
    {
        if (! method_exists($record, 'saveCustomFieldValues')) {
            return;
        }

        $customFieldData = $data['custom_fields'] ?? [];

        if (! empty($customFieldData)) {
            $record->saveCustomFieldValues($customFieldData);
        }
    }

    /**
     * Create a form component for a custom field.
     */
    private function makeFormComponent(CustomField $field): Component
    {
        /** @var Component $component */
        $component = match ($field->type) {
            CustomFieldType::Text => TextInput::make('custom_fields.'.$field->slug),
            CustomFieldType::Number => TextInput::make('custom_fields.'.$field->slug)->numeric(),
            CustomFieldType::Date => DatePicker::make('custom_fields.'.$field->slug),
            CustomFieldType::Checkbox => Checkbox::make('custom_fields.'.$field->slug),
            CustomFieldType::Select => Select::make('custom_fields.'.$field->slug)
                ->options(array_combine($field->options ?? [], $field->options ?? [])),
        };

        return $component
            ->label($field->name)
            ->helperText($field->description);
    }

    /**
     * Create a table column for a custom field.
     */
    private function makeTableColumn(CustomField $field): Column
    {
        return match ($field->type) {
            CustomFieldType::Checkbox => IconColumn::make('custom_field_'.$field->slug)
                ->label($field->name)
                ->boolean()
                ->state(fn (Model $record): bool => (bool) $this->getCustomFieldValueFromRecord($record, $field->slug))
                ->toggleable(),
            CustomFieldType::Date => TextColumn::make('custom_field_'.$field->slug)
                ->label($field->name)
                ->date()
                ->state(fn (Model $record): ?string => $this->getCustomFieldValueFromRecord($record, $field->slug))
                ->toggleable(),
            default => TextColumn::make('custom_field_'.$field->slug)
                ->label($field->name)
                ->state(fn (Model $record): ?string => $this->getCustomFieldValueFromRecord($record, $field->slug))
                ->toggleable(),
        };
    }

    /**
     * Create an infolist entry for a custom field.
     */
    private function makeInfolistEntry(CustomField $field): Entry
    {
        return match ($field->type) {
            CustomFieldType::Checkbox => IconEntry::make('custom_field_'.$field->slug)
                ->label($field->name)
                ->boolean()
                ->state(fn (Model $record): bool => (bool) $this->getCustomFieldValueFromRecord($record, $field->slug)),
            CustomFieldType::Date => TextEntry::make('custom_field_'.$field->slug)
                ->label($field->name)
                ->date()
                ->state(fn (Model $record): ?string => $this->getCustomFieldValueFromRecord($record, $field->slug))
                ->placeholder('-'),
            default => TextEntry::make('custom_field_'.$field->slug)
                ->label($field->name)
                ->state(fn (Model $record): ?string => $this->getCustomFieldValueFromRecord($record, $field->slug))
                ->placeholder('-'),
        };
    }

    /**
     * Get a custom field value from a record.
     */
    private function getCustomFieldValueFromRecord(Model $record, string $slug): mixed
    {
        if (! method_exists($record, 'getCustomFieldValue')) {
            return null;
        }

        return $record->getCustomFieldValue($slug);
    }
}
