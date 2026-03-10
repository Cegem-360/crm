<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use App\Enums\CustomFieldModel;
use App\Services\CustomFieldService;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\Column;

trait HasCustomFieldsSchema
{
    /**
     * Get custom field form components wrapped in a section.
     *
     * @return array<Section>
     */
    protected static function getCustomFieldsFormSection(CustomFieldModel $modelType): array
    {
        $components = resolve(CustomFieldService::class)->getFormComponents($modelType);

        return self::wrapInCustomFieldsSection($components);
    }

    /**
     * Get custom field table columns.
     *
     * @return array<Column>
     */
    protected static function getCustomFieldsTableColumns(CustomFieldModel $modelType): array
    {
        return resolve(CustomFieldService::class)->getTableColumns($modelType);
    }

    /**
     * Get custom field infolist components wrapped in a section.
     *
     * @return array<Section>
     */
    protected static function getCustomFieldsInfolistSection(CustomFieldModel $modelType): array
    {
        $components = resolve(CustomFieldService::class)->getInfolistComponents($modelType);

        return self::wrapInCustomFieldsSection($components);
    }

    /**
     * Wrap custom field components in a collapsible section.
     *
     * @return array<Section>
     */
    private static function wrapInCustomFieldsSection(array $components): array
    {
        if ($components === []) {
            return [];
        }

        return [
            Section::make(__('Custom Fields'))
                ->schema($components)
                ->columns(2)
                ->collapsible(),
        ];
    }
}
