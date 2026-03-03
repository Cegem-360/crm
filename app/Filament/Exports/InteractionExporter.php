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
                ->label(__('ID')),
            ExportColumn::make('customer.unique_identifier')
                ->label(__('Customer Identifier')),
            ExportColumn::make('customer.name')
                ->label(__('Customer Name')),
            ExportColumn::make('contact.name')
                ->label(__('Contact Name')),
            ExportColumn::make('contact.email')
                ->label(__('Contact Email')),
            ExportColumn::make('user.name')
                ->label(__('User Name')),
            ExportColumn::make('user.email')
                ->label(__('User Email')),
            ExportColumn::make('type')
                ->label(__('Type')),
            ExportColumn::make('category')
                ->label(__('Category')),
            ExportColumn::make('channel')
                ->label(__('Channel')),
            ExportColumn::make('direction')
                ->label(__('Direction')),
            ExportColumn::make('status')
                ->label(__('Status')),
            ExportColumn::make('subject')
                ->label(__('Subject')),
            ExportColumn::make('description')
                ->label(__('Description')),
            ExportColumn::make('interaction_date')
                ->label(__('Interaction Date')),
            ExportColumn::make('duration')
                ->label(__('Duration')),
            ExportColumn::make('next_action')
                ->label(__('Next Action')),
            ExportColumn::make('next_action_date')
                ->label(__('Next Action Date')),
            ExportColumn::make('created_at')
                ->label(__('Created At')),
            ExportColumn::make('updated_at')
                ->label(__('Updated At')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __(':count interaction exported successfully.', ['count' => Number::format($export->successful_rows)]);

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to export.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }
}
