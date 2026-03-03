<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Enums\ComplaintSeverity;
use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

final class ComplaintExporter extends Exporter
{
    protected static ?string $model = Complaint::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label(__('ID')),
            ExportColumn::make('title')
                ->label(__('Title')),
            ExportColumn::make('customer.unique_identifier')
                ->label(__('Customer Identifier')),
            ExportColumn::make('customer.name')
                ->label(__('Customer Name')),
            ExportColumn::make('order.order_number')
                ->label(__('Order Number')),
            ExportColumn::make('reporter.name')
                ->label(__('Reported By')),
            ExportColumn::make('assignedUser.name')
                ->label(__('Assigned To')),
            ExportColumn::make('severity')
                ->label(__('Severity'))
                ->formatStateUsing(fn (ComplaintSeverity $state): string => $state->value),
            ExportColumn::make('status')
                ->label(__('Status'))
                ->formatStateUsing(fn (ComplaintStatus $state): string => $state->value),
            ExportColumn::make('description')
                ->label(__('Description')),
            ExportColumn::make('resolution')
                ->label(__('Resolution')),
            ExportColumn::make('reported_at')
                ->label(__('Reported At')),
            ExportColumn::make('resolved_at')
                ->label(__('Resolved At')),
            ExportColumn::make('created_at')
                ->label(__('Created At')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __(':count complaint exported successfully.', ['count' => Number::format($export->successful_rows)]);

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to export.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }
}
