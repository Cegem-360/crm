<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Product;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

final class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label(__('ID')),
            ExportColumn::make('name')
                ->label(__('Name')),
            ExportColumn::make('sku')
                ->label(__('SKU')),
            ExportColumn::make('description')
                ->label(__('Description')),
            ExportColumn::make('category.name')
                ->label(__('Category')),
            ExportColumn::make('unit_price')
                ->label(__('Unit Price')),
            ExportColumn::make('tax_rate')
                ->label(__('Tax Rate')),
            ExportColumn::make('is_active')
                ->label(__('Is Active')),
            ExportColumn::make('created_at')
                ->label(__('Created At')),
            ExportColumn::make('updated_at')
                ->label(__('Updated At')),
            ExportColumn::make('deleted_at')
                ->label(__('Deleted At')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __(':count product exported successfully.', ['count' => Number::format($export->successful_rows)]);

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to export.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }
}
