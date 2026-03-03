<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\ProductCategory;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Number;
use Override;

final class ProductCategoryImporter extends Importer
{
    protected static ?string $model = ProductCategory::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label(__('Name'))
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('parent_name')
                ->label(__('Parent Category Name'))
                ->rules(['nullable', 'string']),
            ImportColumn::make('description')
                ->label(__('Description'))
                ->rules(['nullable', 'string']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __(':count product category imported successfully.', ['count' => Number::format($import->successful_rows)]);

        if (($failedRowsCount = $import->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to import.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }

    #[Override]
    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label(__('Update existing records')),
        ];
    }

    #[Override]
    public function resolveRecord(): ProductCategory
    {
        if (! empty($this->data['parent_name'])) {
            $parent = ProductCategory::query()
                ->where('name', $this->data['parent_name'])
                ->first();

            $this->data['parent_id'] = $parent?->id;
        }

        unset($this->data['parent_name']);

        if ($this->options['updateExisting'] ?? false) {
            return ProductCategory::query()->firstOrNew([
                'name' => $this->data['name'],
            ]);
        }

        return new ProductCategory();
    }
}
