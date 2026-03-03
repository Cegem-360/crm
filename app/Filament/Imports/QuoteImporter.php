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
                ->label(__('Quote Number'))
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('customer_identifier')
                ->label(__('Customer Identifier'))
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('issue_date')
                ->label(__('Issue Date'))
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('valid_until')
                ->label(__('Valid Until'))
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('status')
                ->label(__('Status'))
                ->requiredMapping()
                ->examples(QuoteStatus::cases())
                ->rules(['required']),
            ImportColumn::make('subtotal')
                ->label(__('Subtotal'))
                ->numeric()
                ->rules(['required', 'numeric', 'min:0']),
            ImportColumn::make('discount_amount')
                ->label(__('Discount Amount'))
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('tax_amount')
                ->label(__('Tax Amount'))
                ->numeric()
                ->rules(['required', 'numeric', 'min:0']),
            ImportColumn::make('total')
                ->label(__('Total'))
                ->numeric()
                ->rules(['required', 'numeric', 'min:0']),
            ImportColumn::make('notes')
                ->label(__('Notes'))
                ->rules(['nullable', 'string']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __(':count quote imported successfully.', ['count' => Number::format($import->successful_rows)]);

        if (($failedRowsCount = $import->getFailedRowsCount()) !== 0) {
            $body .= __(' :count failed to import.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }

    #[Override]
    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label(__('Update existing records')),
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
