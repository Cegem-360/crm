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
                ->label(__('ID')),
            ExportColumn::make('invoice_number')
                ->label(__('Invoice Number')),
            ExportColumn::make('customer.unique_identifier')
                ->label(__('Customer Identifier')),
            ExportColumn::make('customer.name')
                ->label(__('Customer Name')),
            ExportColumn::make('order.order_number')
                ->label(__('Order Number')),
            ExportColumn::make('issue_date')
                ->label(__('Issue Date')),
            ExportColumn::make('due_date')
                ->label(__('Due Date')),
            ExportColumn::make('status')
                ->label(__('Status'))
                ->formatStateUsing(fn (InvoiceStatus $state): string => $state->value),
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
            ExportColumn::make('paid_at')
                ->label(__('Paid At')),
            ExportColumn::make('created_at')
                ->label(__('Created At')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __(':count invoice exported successfully.', ['count' => Number::format($export->successful_rows)]);

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to export.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }
}
