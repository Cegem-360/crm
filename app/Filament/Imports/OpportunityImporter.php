<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Enums\CustomerType;
use App\Enums\OpportunityStage;
use App\Models\Customer;
use App\Models\Opportunity;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Override;

final class OpportunityImporter extends Importer
{
    protected static ?string $model = Opportunity::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('customer_unique_identifier')
                ->label('Unique Identifier')
                ->requiredMapping()
                ->rules(['nullable', 'string', 'max:255'])
                ->example('CUST-123456'),

            ImportColumn::make('customer_name')
                ->label('Name')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),

            ImportColumn::make('customer_type')
                ->label('Type')
                ->requiredMapping()
                ->rules(['required', 'in:individual,company'])
                ->example('company'),

            ImportColumn::make('customer_tax_number')
                ->label('Tax Number')
                ->rules(['nullable', 'string', 'max:255']),

            ImportColumn::make('customer_registration_number')
                ->label('Registration Number')
                ->rules(['nullable', 'string', 'max:255']),

            ImportColumn::make('customer_email')
                ->label('Email Address')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),

            ImportColumn::make('customer_phone')
                ->label('Phone')
                ->rules(['nullable', 'string', 'max:255']),

            ImportColumn::make('source')
                ->label('Source')
                ->requiredMapping()
                ->rules(['required', 'in:email,sms,chat,social'])
                ->example('email'),

            ImportColumn::make('title')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('description')
                ->rules(['nullable', 'string']),

            ImportColumn::make('value')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0']),

            ImportColumn::make('probability')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'min:0', 'max:100']),

            ImportColumn::make('stage')
                ->requiredMapping()
                ->rules(['required', 'in:lead,qualified,proposal,negotiation,sended_quotation,lost_quotation'])
                ->example('lead'),

            ImportColumn::make('expected_close_date')
                ->rules(['nullable', 'date']),

            ImportColumn::make('assigned_to')
                ->label('Assigned User Email')
                ->rules(['nullable', 'email', 'exists:users,email']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your opportunity import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if (($failedRowsCount = $import->getFailedRowsCount()) !== 0) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }

    #[Override]
    public function resolveRecord(): Opportunity
    {
        $customer = $this->resolveCustomer();

        $assignedUserId = $this->resolveAssignedUserId();

        return Opportunity::query()->make([
            'customer_id' => $customer->id,
            'title' => $this->data['title'],
            'description' => $this->data['description'] ?? null,
            'value' => $this->data['value'] ?? null,
            'probability' => $this->data['probability'],
            'stage' => OpportunityStage::from($this->data['stage']),
            'expected_close_date' => $this->data['expected_close_date'] ?? null,
            'assigned_to' => $assignedUserId,
        ]);
    }

    private function resolveCustomer(): Customer
    {
        $customerData = [
            'unique_identifier' => $this->data['customer_unique_identifier'],
            'name' => $this->data['customer_name'],
            'type' => CustomerType::from($this->data['customer_type']),
            'tax_number' => $this->data['customer_tax_number'] ?? null,
            'registration_number' => $this->data['customer_registration_number'] ?? null,
            'email' => $this->data['customer_email'],
            'phone' => $this->data['customer_phone'] ?? null,
            'is_active' => true,
        ];

        $customer = Customer::query()
            ->where(function (Builder $query): void {
                $query->where('unique_identifier', $this->data['customer_unique_identifier'])
                    ->orWhere('name', $this->data['customer_name'])
                    ->orWhere('email', $this->data['customer_email']);

                if (! empty($this->data['customer_tax_number'])) {
                    $query->orWhere('tax_number', $this->data['customer_tax_number']);
                }
            })
            ->first();

        if ($customer) {
            $customer->update($customerData);
        } else {
            $customer = Customer::query()->create($customerData);
        }

        return $customer;
    }

    private function resolveAssignedUserId(): ?int
    {
        if (empty($this->data['assigned_to'])) {
            return null;
        }

        return User::query()
            ->where('email', $this->data['assigned_to'])
            ->value('id');
    }
}
