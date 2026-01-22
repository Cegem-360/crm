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
                ->label('Customer Identifier')
                ->exampleHeader('customer_identifier')
                ->examples(['CUST-001', 'ABC Company']),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->rules(['nullable', 'email', 'max:255']),
            ImportColumn::make('phone')
                ->rules(['nullable', 'max:50']),
            ImportColumn::make('position')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('is_primary')
                ->boolean()
                ->rules(['nullable', 'boolean']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your contact import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if (($failedRowsCount = $import->getFailedRowsCount()) !== 0) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label('Update existing records')
                ->helperText('Match existing contacts by customer + email or customer + name'),
        ];
    }

    public function resolveRecord(): CustomerContact
    {
        $customerIdentifier = $this->data['customer_identifier'] ?? null;

        if (empty($customerIdentifier)) {
            throw ValidationException::withMessages([
                'customer_identifier' => 'Customer identifier is required.',
            ]);
        }

        $this->resolvedCustomer = $this->findCustomer($customerIdentifier);

        if ($this->resolvedCustomer === null) {
            throw ValidationException::withMessages([
                'customer_identifier' => "Customer not found: {$customerIdentifier}",
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
        if ($this->resolvedCustomer !== null) {
            $this->record->customer_id = $this->resolvedCustomer->id;
        }
    }

    private function findCustomer(string $identifier): ?Customer
    {
        return Customer::query()
            ->where('unique_identifier', $identifier)
            ->orWhere('name', $identifier)
            ->orWhereHas('company', fn ($query) => $query->where('email', $identifier))
            ->first();
    }

    private function findExistingContact(): ?CustomerContact
    {
        if ($this->resolvedCustomer === null) {
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
