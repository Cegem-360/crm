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
                ->label('ID'),
            ExportColumn::make('name'),
            ExportColumn::make('type')->formatStateUsing(fn (DiscountType $state): string => $state->value),
            ExportColumn::make('value_type')->formatStateUsing(fn (DiscountValueType $state): string => $state->value),
            ExportColumn::make('value'),
            ExportColumn::make('min_quantity'),
            ExportColumn::make('min_value'),
            ExportColumn::make('valid_from'),
            ExportColumn::make('valid_until'),
            ExportColumn::make('customer.name')
                ->label('Customer'),
            ExportColumn::make('product.name')
                ->label('Product'),
            ExportColumn::make('is_active'),
            ExportColumn::make('description'),
            ExportColumn::make('created_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your discount export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
