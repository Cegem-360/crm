<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Enums\QuoteStatus;
use App\Models\Quote;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

final class QuoteExporter extends Exporter
{
    protected static ?string $model = Quote::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label(__('ID')),
            ExportColumn::make('quote_number')
                ->label(__('Quote Number')),
            ExportColumn::make('customer.unique_identifier')
                ->label(__('Customer Identifier')),
            ExportColumn::make('customer.name')
                ->label(__('Customer Name')),
            ExportColumn::make('opportunity.title')
                ->label(__('Opportunity')),
            ExportColumn::make('issue_date')
                ->label(__('Issue Date')),
            ExportColumn::make('valid_until')
                ->label(__('Valid Until')),
            ExportColumn::make('status')
                ->label(__('Status'))
                ->formatStateUsing(fn (QuoteStatus $state): string => $state->value),
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
        $body = __(':count quote exported successfully.', ['count' => Number::format($export->successful_rows)]);

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to export.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }
}
