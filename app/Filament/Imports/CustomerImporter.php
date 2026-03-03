<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Enums\CustomerType;
use App\Models\Customer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Number;
use Override;

final class CustomerImporter extends Importer
{
    protected static ?string $model = Customer::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('unique_identifier')
                ->label(__('Unique Identifier'))
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('name')
                ->label(__('Name'))
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('type')
                ->label(__('Type'))
                ->requiredMapping()
                ->examples(CustomerType::cases())
                ->rules(['required']),
            ImportColumn::make('tax_number')
                ->label(__('Tax Number')),
            ImportColumn::make('registration_number')
                ->label(__('Registration Number')),
            ImportColumn::make('email')
                ->label(__('Email'))
                ->rules(['email']),
            ImportColumn::make('phone')
                ->label(__('Phone')),
            ImportColumn::make('notes')
                ->label(__('Notes')),
            ImportColumn::make('is_active')
                ->label(__('Is Active'))
                ->requiredMappingForNewRecordsOnly()
                ->boolean()
                ->rules(['required', 'boolean']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __(':count customer imported successfully.', ['count' => Number::format($import->successful_rows)]);

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
    public function resolveRecord(): Customer
    {
        if ($this->options['updateExisting'] ?? false) {
            return Customer::query()->firstOrNew([
                'unique_identifier' => $this->data['unique_identifier'],
            ]);
        }

        return new Customer();
    }
}
