<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Enums\ComplaintSeverity;
use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\Order;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use Illuminate\Validation\ValidationException;
use Override;

final class ComplaintImporter extends Importer
{
    protected static ?string $model = Complaint::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('customer_identifier')
                ->label(__('Customer Identifier'))
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('order_number')
                ->label(__('Order Number'))
                ->rules(['nullable', 'string']),
            ImportColumn::make('severity')
                ->requiredMapping()
                ->examples(ComplaintSeverity::cases())
                ->rules(['required']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->examples(ComplaintStatus::cases())
                ->rules(['required']),
            ImportColumn::make('description')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('reported_at')
                ->rules(['nullable', 'date']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your complaint import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if (($failedRowsCount = $import->getFailedRowsCount()) !== 0) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }

    #[Override]
    public function resolveRecord(): Complaint
    {
        $customer = Customer::query()
            ->where('unique_identifier', $this->data['customer_identifier'])
            ->orWhere('name', $this->data['customer_identifier'])
            ->first();

        if (! $customer) {
            throw ValidationException::withMessages([
                'customer_identifier' => 'Customer not found: '.$this->data['customer_identifier'],
            ]);
        }

        $this->data['customer_id'] = $customer->id;
        $this->data['reported_by'] = Auth::id();
        $this->data['reported_at'] ??= now();

        if (! empty($this->data['order_number'])) {
            $order = Order::query()
                ->where('order_number', $this->data['order_number'])
                ->first();

            $this->data['order_id'] = $order?->id;
        }

        unset($this->data['customer_identifier'], $this->data['order_number']);

        return new Complaint();
    }
}
