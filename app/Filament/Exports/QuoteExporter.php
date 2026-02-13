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
            ExportColumn::make('quote_number'),
            ExportColumn::make('customer.unique_identifier'),
            ExportColumn::make('customer.name'),
            ExportColumn::make('opportunity.title')
                ->label(__('Opportunity')),
            ExportColumn::make('issue_date'),
            ExportColumn::make('valid_until'),
            ExportColumn::make('status')->formatStateUsing(fn (QuoteStatus $state): string => $state->value),
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
        $body = 'Your quote export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
