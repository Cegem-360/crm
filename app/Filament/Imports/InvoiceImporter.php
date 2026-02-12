<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Number;
use Illuminate\Validation\ValidationException;
use Override;

final class InvoiceImporter extends Importer
{
    protected static ?string $model = Invoice::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('invoice_number')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('customer_identifier')
                ->label('Customer Identifier')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('order_number')
                ->label('Order Number')
                ->rules(['nullable', 'string']),
            ImportColumn::make('issue_date')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('due_date')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->examples(InvoiceStatus::cases())
                ->rules(['required']),
            ImportColumn::make('subtotal')
                ->numeric()
                ->rules(['required', 'numeric', 'min:0']),
            ImportColumn::make('discount_amount')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('tax_amount')
                ->numeric()
                ->rules(['required', 'numeric', 'min:0']),
            ImportColumn::make('total')
                ->numeric()
                ->rules(['required', 'numeric', 'min:0']),
            ImportColumn::make('notes')
                ->rules(['nullable', 'string']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your invoice import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if (($failedRowsCount = $import->getFailedRowsCount()) !== 0) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }

    #[Override]
    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label('Update existing records'),
        ];
    }

    #[Override]
    public function resolveRecord(): Invoice
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

        if (! empty($this->data['order_number'])) {
            $order = Order::query()
                ->where('order_number', $this->data['order_number'])
                ->first();

            $this->data['order_id'] = $order?->id;
        }

        unset($this->data['customer_identifier'], $this->data['order_number']);

        if ($this->options['updateExisting'] ?? false) {
            return Invoice::query()->firstOrNew([
                'invoice_number' => $this->data['invoice_number'],
            ]);
        }

        return new Invoice();
    }
}
