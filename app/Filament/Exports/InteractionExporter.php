<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Interaction;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

final class InteractionExporter extends Exporter
{
    protected static ?string $model = Interaction::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('customer.unique_identifier')
                ->label('Customer Identifier'),
            ExportColumn::make('customer.name')
                ->label('Customer Name'),
            ExportColumn::make('contact.name')
                ->label('Contact Name'),
            ExportColumn::make('contact.email')
                ->label('Contact Email'),
            ExportColumn::make('user.name')
                ->label('User Name'),
            ExportColumn::make('user.email')
                ->label('User Email'),
            ExportColumn::make('type'),
            ExportColumn::make('category'),
            ExportColumn::make('channel'),
            ExportColumn::make('direction'),
            ExportColumn::make('status'),
            ExportColumn::make('subject'),
            ExportColumn::make('description'),
            ExportColumn::make('interaction_date'),
            ExportColumn::make('duration'),
            ExportColumn::make('next_action'),
            ExportColumn::make('next_action_date'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your interaction export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
