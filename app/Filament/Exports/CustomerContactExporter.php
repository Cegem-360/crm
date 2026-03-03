<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\CustomerContact;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

final class CustomerContactExporter extends Exporter
{
    protected static ?string $model = CustomerContact::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label(__('ID')),
            ExportColumn::make('customer.unique_identifier')
                ->label(__('Customer Identifier')),
            ExportColumn::make('customer.name')
                ->label(__('Customer Name')),
            ExportColumn::make('name')
                ->label(__('Name')),
            ExportColumn::make('email')
                ->label(__('Email')),
            ExportColumn::make('phone')
                ->label(__('Phone')),
            ExportColumn::make('position')
                ->label(__('Position')),
            ExportColumn::make('is_primary')
                ->label(__('Is Primary')),
            ExportColumn::make('created_at')
                ->label(__('Created At')),
            ExportColumn::make('updated_at')
                ->label(__('Updated At')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __(':count contact exported successfully.', ['count' => Number::format($export->successful_rows)]);

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to export.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }
}
