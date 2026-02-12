<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

final class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('order_number'),
            ExportColumn::make('customer.unique_identifier'),
            ExportColumn::make('customer.name'),
            ExportColumn::make('quote.quote_number'),
            ExportColumn::make('order_date'),
            ExportColumn::make('status')->formatStateUsing(fn (OrderStatus $state): string => $state->value),
            ExportColumn::make('subtotal'),
            ExportColumn::make('discount_amount'),
            ExportColumn::make('tax_amount'),
            ExportColumn::make('total'),
            ExportColumn::make('notes'),
            ExportColumn::make('created_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your order export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
