<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Override;

final class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label(__('Name'))
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('sku')
                ->label(__('SKU'))
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('description')
                ->label(__('Description')),
            ImportColumn::make('category')
                ->label(__('Category'))
                ->relationship(),
            ImportColumn::make('unit_price')
                ->label(__('Unit Price'))
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('tax_rate')
                ->label(__('Tax Rate'))
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('is_active')
                ->label(__('Is Active'))
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __(':count product imported successfully.', ['count' => Number::format($import->successful_rows)]);

        if (($failedRowsCount = $import->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to import.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }

    #[Override]
    public function resolveRecord(): Product
    {
        return Product::query()->firstOrNew([
            'name' => $this->data['name'],
        ]);
    }
}
