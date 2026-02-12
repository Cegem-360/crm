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
                ->label('ID'),
            ExportColumn::make('customer.unique_identifier'),
            ExportColumn::make('customer.name'),
            ExportColumn::make('title'),
            ExportColumn::make('description'),
            ExportColumn::make('value'),
            ExportColumn::make('probability'),
            ExportColumn::make('stage')->formatStateUsing(fn (OpportunityStage $state): string => $state->value),
            ExportColumn::make('expected_close_date'),
            ExportColumn::make('assignedUser.name')
                ->label('Assigned To'),
            ExportColumn::make('assignedUser.email')
                ->label('Assigned Email'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your opportunity export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
