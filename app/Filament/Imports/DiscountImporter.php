<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Enums\DiscountType;
use App\Enums\DiscountValueType;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Number;
use Override;

final class DiscountImporter extends Importer
{
    protected static ?string $model = Discount::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('type')
                ->requiredMapping()
                ->examples(DiscountType::cases())
                ->rules(['required']),
            ImportColumn::make('value_type')
                ->requiredMapping()
                ->examples(DiscountValueType::cases())
                ->rules(['required']),
            ImportColumn::make('value')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'numeric', 'min:0']),
            ImportColumn::make('min_quantity')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('min_value')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('valid_from')
                ->rules(['nullable', 'date']),
            ImportColumn::make('valid_until')
                ->rules(['nullable', 'date']),
            ImportColumn::make('customer_identifier')
                ->label(__('Customer Identifier'))
                ->rules(['nullable', 'string']),
            ImportColumn::make('product_name')
                ->label(__('Product Name'))
                ->rules(['nullable', 'string']),
            ImportColumn::make('is_active')
                ->boolean()
                ->rules(['nullable', 'boolean']),
            ImportColumn::make('description')
                ->rules(['nullable', 'string']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your discount import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

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
                ->label(__('Update existing records')),
        ];
    }

    #[Override]
    public function resolveRecord(): Discount
    {
        if (! empty($this->data['customer_identifier'])) {
            $customer = Customer::query()
                ->where('unique_identifier', $this->data['customer_identifier'])
                ->orWhere('name', $this->data['customer_identifier'])
                ->first();

            $this->data['customer_id'] = $customer?->id;
        }

        if (! empty($this->data['product_name'])) {
            $product = Product::query()
                ->where('name', $this->data['product_name'])
                ->first();

            $this->data['product_id'] = $product?->id;
        }

        unset($this->data['customer_identifier'], $this->data['product_name']);

        if ($this->options['updateExisting'] ?? false) {
            return Discount::query()->firstOrNew([
                'name' => $this->data['name'],
            ]);
        }

        return new Discount();
    }
}
