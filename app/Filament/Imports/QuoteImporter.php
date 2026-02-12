<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Enums\QuoteStatus;
use App\Models\Customer;
use App\Models\Quote;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Number;
use Illuminate\Validation\ValidationException;
use Override;

final class QuoteImporter extends Importer
{
    protected static ?string $model = Quote::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('quote_number')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('customer_identifier')
                ->label('Customer Identifier')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('issue_date')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('valid_until')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->examples(QuoteStatus::cases())
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
        $body = 'Your quote import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

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
    public function resolveRecord(): Quote
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

        unset($this->data['customer_identifier']);

        if ($this->options['updateExisting'] ?? false) {
            return Quote::query()->firstOrNew([
                'quote_number' => $this->data['quote_number'],
            ]);
        }

        return new Quote();
    }
}
