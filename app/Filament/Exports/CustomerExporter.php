<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Enums\CustomerType;
use App\Models\Customer;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

final class CustomerExporter extends Exporter
{
    protected static ?string $model = Customer::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label(__('ID')),
            ExportColumn::make('unique_identifier')
                ->label(__('Unique Identifier')),
            ExportColumn::make('name')
                ->label(__('Name')),
            ExportColumn::make('type')
                ->label(__('Type'))
                ->formatStateUsing(fn (CustomerType $state): string => $state->value),
            ExportColumn::make('tax_number')
                ->label(__('Tax Number')),
            ExportColumn::make('registration_number')
                ->label(__('Registration Number')),
            ExportColumn::make('email')
                ->label(__('Email')),
            ExportColumn::make('phone')
                ->label(__('Phone')),
            ExportColumn::make('notes')
                ->label(__('Notes')),
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
        $body = __(':count customer exported successfully.', ['count' => Number::format($export->successful_rows)]);

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to export.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }
}
