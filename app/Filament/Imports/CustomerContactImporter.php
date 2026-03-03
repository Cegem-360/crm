<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\Customer;
use App\Models\CustomerContact;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Number;
use Illuminate\Validation\ValidationException;
use Override;

final class CustomerContactImporter extends Importer
{
    protected static ?string $model = CustomerContact::class;

    private ?Customer $resolvedCustomer = null;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('customer_identifier')
                ->requiredMapping()
                ->rules(['required'])
                ->label(__('Customer Identifier'))
                ->exampleHeader('customer_identifier')
                ->examples(['CUST-001', 'ABC Company']),
            ImportColumn::make('name')
                ->label(__('Name'))
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->label(__('Email'))
                ->rules(['nullable', 'email', 'max:255']),
            ImportColumn::make('phone')
                ->label(__('Phone'))
                ->rules(['nullable', 'max:50']),
            ImportColumn::make('position')
                ->label(__('Position'))
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('is_primary')
                ->label(__('Is Primary'))
                ->boolean()
                ->rules(['nullable', 'boolean']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __(':count contact imported successfully.', ['count' => Number::format($import->successful_rows)]);

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
                ->label(__('Update existing records'))
                ->helperText(__('Match existing contacts by customer + email or customer + name')),
        ];
    }

    #[Override]
    public function resolveRecord(): CustomerContact
    {
        $customerIdentifier = $this->data['customer_identifier'] ?? null;

        if (empty($customerIdentifier)) {
            throw ValidationException::withMessages([
                'customer_identifier' => 'Customer identifier is required.',
            ]);
        }

        $this->resolvedCustomer = $this->findCustomer($customerIdentifier);

        if (! $this->resolvedCustomer instanceof Customer) {
            throw ValidationException::withMessages([
                'customer_identifier' => 'Customer not found: '.$customerIdentifier,
            ]);
        }

        if ($this->options['updateExisting'] ?? false) {
            return $this->findExistingContact() ?? new CustomerContact();
        }

        return new CustomerContact();
    }

    protected function beforeFill(): void
    {
        unset($this->data['customer_identifier']);
    }

    protected function afterFill(): void
    {
        if ($this->resolvedCustomer instanceof Customer) {
            $this->record->customer_id = $this->resolvedCustomer->id;
        }
    }

    private function findCustomer(string $identifier): ?Customer
    {
        return Customer::query()
            ->where('unique_identifier', $identifier)
            ->orWhere('name', $identifier)
            ->orWhere('email', $identifier)
            ->first();
    }

    private function findExistingContact(): ?CustomerContact
    {
        if (! $this->resolvedCustomer instanceof Customer) {
            return null;
        }

        $email = $this->data['email'] ?? null;
        $name = $this->data['name'] ?? null;

        $query = CustomerContact::query()
            ->where('customer_id', $this->resolvedCustomer->id);

        if (! empty($email)) {
            return $query->where('email', $email)->first();
        }

        if (! empty($name)) {
            return $query->where('name', $name)->first();
        }

        return null;
    }
}
