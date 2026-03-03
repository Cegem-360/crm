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
                ->label(__('ID')),
            ExportColumn::make('order_number')
                ->label(__('Order Number')),
            ExportColumn::make('customer.unique_identifier')
                ->label(__('Customer Identifier')),
            ExportColumn::make('customer.name')
                ->label(__('Customer Name')),
            ExportColumn::make('quote.quote_number')
                ->label(__('Quote Number')),
            ExportColumn::make('order_date')
                ->label(__('Order Date')),
            ExportColumn::make('status')
                ->label(__('Status'))
                ->formatStateUsing(fn (OrderStatus $state): string => $state->value),
            ExportColumn::make('subtotal')
                ->label(__('Subtotal')),
            ExportColumn::make('discount_amount')
                ->label(__('Discount Amount')),
            ExportColumn::make('tax_amount')
                ->label(__('Tax Amount')),
            ExportColumn::make('total')
                ->label(__('Total')),
            ExportColumn::make('notes')
                ->label(__('Notes')),
            ExportColumn::make('created_at')
                ->label(__('Created At')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __(':count order exported successfully.', ['count' => Number::format($export->successful_rows)]);

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to export.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }
}
