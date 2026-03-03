<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Enums\DiscountType;
use App\Enums\DiscountValueType;
use App\Models\Discount;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

final class DiscountExporter extends Exporter
{
    protected static ?string $model = Discount::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label(__('ID')),
            ExportColumn::make('name')
                ->label(__('Name')),
            ExportColumn::make('type')
                ->label(__('Type'))
                ->formatStateUsing(fn (DiscountType $state): string => $state->value),
            ExportColumn::make('value_type')
                ->label(__('Value Type'))
                ->formatStateUsing(fn (DiscountValueType $state): string => $state->value),
            ExportColumn::make('value')
                ->label(__('Value')),
            ExportColumn::make('min_quantity')
                ->label(__('Min Quantity')),
            ExportColumn::make('min_value')
                ->label(__('Min Value')),
            ExportColumn::make('valid_from')
                ->label(__('Valid From')),
            ExportColumn::make('valid_until')
                ->label(__('Valid Until')),
            ExportColumn::make('customer.name')
                ->label(__('Customer')),
            ExportColumn::make('product.name')
                ->label(__('Product')),
            ExportColumn::make('is_active')
                ->label(__('Is Active')),
            ExportColumn::make('description')
                ->label(__('Description')),
            ExportColumn::make('created_at')
                ->label(__('Created At')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __(':count discount exported successfully.', ['count' => Number::format($export->successful_rows)]);

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to export.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }
}
