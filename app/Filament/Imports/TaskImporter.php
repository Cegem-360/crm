<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\Customer;
use App\Models\Task;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use Override;

final class TaskImporter extends Importer
{
    protected static ?string $model = Task::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('customer_identifier')
                ->label('Customer Identifier')
                ->rules(['nullable', 'string']),
            ImportColumn::make('assigned_to_email')
                ->label('Assigned To (Email)')
                ->requiredMapping()
                ->rules(['required', 'email']),
            ImportColumn::make('priority')
                ->requiredMapping()
                ->rules(['required', 'in:low,medium,high,urgent']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules(['required', 'in:pending,in_progress,completed,cancelled']),
            ImportColumn::make('description')
                ->rules(['nullable', 'string']),
            ImportColumn::make('due_date')
                ->rules(['nullable', 'date']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your task import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if (($failedRowsCount = $import->getFailedRowsCount()) !== 0) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }

    #[Override]
    public function resolveRecord(): Task
    {
        if (! empty($this->data['customer_identifier'])) {
            $customer = Customer::query()
                ->where('unique_identifier', $this->data['customer_identifier'])
                ->orWhere('name', $this->data['customer_identifier'])
                ->first();

            $this->data['customer_id'] = $customer?->id;
        }

        if (! empty($this->data['assigned_to_email'])) {
            $this->data['assigned_to'] = User::query()
                ->where('email', $this->data['assigned_to_email'])
                ->value('id');
        }

        $this->data['assigned_by'] = Auth::id();

        unset($this->data['customer_identifier'], $this->data['assigned_to_email']);

        return new Task();
    }
}
