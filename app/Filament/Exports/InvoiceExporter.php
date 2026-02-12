<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

final class InvoiceExporter extends Exporter
{
    protected static ?string $model = Invoice::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('invoice_number'),
            ExportColumn::make('customer.unique_identifier'),
            ExportColumn::make('customer.name'),
            ExportColumn::make('order.order_number'),
            ExportColumn::make('issue_date'),
            ExportColumn::make('due_date'),
            ExportColumn::make('status')->formatStateUsing(fn (InvoiceStatus $state): string => $state->value),
            ExportColumn::make('subtotal'),
            ExportColumn::make('discount_amount'),
            ExportColumn::make('tax_amount'),
            ExportColumn::make('total'),
            ExportColumn::make('notes'),
            ExportColumn::make('paid_at'),
            ExportColumn::make('created_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your invoice export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
