<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Enums\OpportunityStage;
use App\Models\Opportunity;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

final class OpportunityExporter extends Exporter
{
    protected static ?string $model = Opportunity::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label(__('ID')),
            ExportColumn::make('customer.unique_identifier')
                ->label(__('Customer Identifier')),
            ExportColumn::make('customer.name')
                ->label(__('Customer Name')),
            ExportColumn::make('title')
                ->label(__('Title')),
            ExportColumn::make('description')
                ->label(__('Description')),
            ExportColumn::make('value')
                ->label(__('Value')),
            ExportColumn::make('probability')
                ->label(__('Probability')),
            ExportColumn::make('stage')
                ->label(__('Stage'))
                ->formatStateUsing(fn (OpportunityStage $state): string => $state->value),
            ExportColumn::make('expected_close_date')
                ->label(__('Expected Close Date')),
            ExportColumn::make('assignedUser.name')
                ->label(__('Assigned To')),
            ExportColumn::make('assignedUser.email')
                ->label(__('Assigned Email')),
            ExportColumn::make('created_at')
                ->label(__('Created At')),
            ExportColumn::make('updated_at')
                ->label(__('Updated At')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __(':count opportunity exported successfully.', ['count' => Number::format($export->successful_rows)]);

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to export.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }
}
