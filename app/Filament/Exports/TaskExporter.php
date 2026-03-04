<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

final class TaskExporter extends Exporter
{
    protected static ?string $model = Task::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label(__('ID')),
            ExportColumn::make('title')
                ->label(__('Title')),
            ExportColumn::make('customer.name')
                ->label(__('Customer')),
            ExportColumn::make('assignedUser.name')
                ->label(__('Assigned To')),
            ExportColumn::make('assignedUser.email')
                ->label(__('Assigned Email')),
            ExportColumn::make('assigner.name')
                ->label(__('Assigned By')),
            ExportColumn::make('priority')
                ->label(__('Priority'))
                ->formatStateUsing(fn (TaskPriority $state): string => $state->value),
            ExportColumn::make('status')
                ->label(__('Status'))
                ->formatStateUsing(fn (TaskStatus $state): string => $state->value),
            ExportColumn::make('description')
                ->label(__('Description')),
            ExportColumn::make('due_date')
                ->label(__('Due Date')),
            ExportColumn::make('completed_at')
                ->label(__('Completed At')),
            ExportColumn::make('created_at')
                ->label(__('Created At')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __(':count task exported successfully.', ['count' => Number::format($export->successful_rows)]);

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to export.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }
}
