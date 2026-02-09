<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use App\Enums\CustomFieldModel;
use App\Services\CustomFieldService;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\Column;

trait HasCustomFieldsSchema
{
    /**
     * Get custom field form components wrapped in a section.
     *
     * @return array<Component>
     */
    protected static function getCustomFieldsFormSection(CustomFieldModel $modelType): array
    {
        $service = app(CustomFieldService::class);
        $components = $service->getFormComponents($modelType);

        if (empty($components)) {
            return [];
        }

        return [
            Section::make(__('Custom Fields'))
                ->schema($components)
                ->columns(2)
                ->collapsible(),
        ];
    }

    /**
     * Get custom field table columns.
     *
     * @return array<Column>
     */
    protected static function getCustomFieldsTableColumns(CustomFieldModel $modelType): array
    {
        return app(CustomFieldService::class)->getTableColumns($modelType);
    }

    /**
     * Get custom field infolist entries wrapped in a section.
     *
     * @return array<Component>
     */
    protected static function getCustomFieldsInfolistSection(CustomFieldModel $modelType): array
    {
        $service = app(CustomFieldService::class);
        $entries = $service->getInfolistEntries($modelType);

        if (empty($entries)) {
            return [];
        }

        return [
            InfolistSection::make(__('Custom Fields'))
                ->schema($entries)
                ->columns(2)
                ->collapsible(),
        ];
    }
}
