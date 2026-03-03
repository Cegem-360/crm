<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Enums\InteractionCategory;
use App\Enums\InteractionChannel;
use App\Enums\InteractionDirection;
use App\Enums\InteractionStatus;
use App\Enums\InteractionType;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\Interaction;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Number;
use Illuminate\Validation\ValidationException;
use Override;

final class InteractionImporter extends Importer
{
    protected static ?string $model = Interaction::class;

    private ?Customer $resolvedCustomer = null;

    private ?CustomerContact $resolvedContact = null;

    private ?User $resolvedUser = null;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('customer_identifier')
                ->requiredMapping()
                ->rules(['required'])
                ->label(__('Customer Identifier'))
                ->exampleHeader('customer_identifier')
                ->examples(['CUST-001', 'ABC Company']),
            ImportColumn::make('contact_email')
                ->label(__('Contact Email'))
                ->rules(['nullable', 'email']),
            ImportColumn::make('user_email')
                ->requiredMapping()
                ->label(__('User Email'))
                ->rules(['required', 'email']),
            ImportColumn::make('type')
                ->label(__('Type'))
                ->requiredMapping()
                ->rules(['required'])
                ->examples(array_map(fn (InteractionType $case) => $case->value, InteractionType::cases())),
            ImportColumn::make('category')
                ->label(__('Category'))
                ->rules(['nullable'])
                ->examples(array_map(fn (InteractionCategory $case) => $case->value, InteractionCategory::cases())),
            ImportColumn::make('channel')
                ->label(__('Channel'))
                ->rules(['nullable'])
                ->examples(array_map(fn (InteractionChannel $case) => $case->value, InteractionChannel::cases())),
            ImportColumn::make('direction')
                ->label(__('Direction'))
                ->rules(['nullable'])
                ->examples(array_map(fn (InteractionDirection $case) => $case->value, InteractionDirection::cases())),
            ImportColumn::make('status')
                ->label(__('Status'))
                ->requiredMapping()
                ->rules(['required'])
                ->examples(array_map(fn (InteractionStatus $case) => $case->value, InteractionStatus::cases())),
            ImportColumn::make('subject')
                ->label(__('Subject'))
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('description')
                ->label(__('Description'))
                ->rules(['nullable']),
            ImportColumn::make('interaction_date')
                ->label(__('Interaction Date'))
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('duration')
                ->label(__('Duration'))
                ->numeric()
                ->rules(['nullable', 'integer', 'min:0']),
            ImportColumn::make('next_action')
                ->label(__('Next Action'))
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('next_action_date')
                ->label(__('Next Action Date'))
                ->rules(['nullable', 'date']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __(':count interaction imported successfully.', ['count' => Number::format($import->successful_rows)]);

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
                ->helperText(__('Match existing interactions by customer + subject + interaction_date')),
        ];
    }

    #[Override]
    public function resolveRecord(): Interaction
    {
        $this->resolveCustomer();
        $this->resolveContact();
        $this->resolveUser();

        if ($this->options['updateExisting'] ?? false) {
            return $this->findExistingInteraction() ?? new Interaction();
        }

        return new Interaction();
    }

    protected function beforeFill(): void
    {
        unset($this->data['customer_identifier'], $this->data['contact_email'], $this->data['user_email']);
    }

    protected function afterFill(): void
    {
        if ($this->resolvedCustomer instanceof Customer) {
            $this->record->customer_id = $this->resolvedCustomer->id;
        }

        if ($this->resolvedContact instanceof CustomerContact) {
            $this->record->customer_contact_id = $this->resolvedContact->id;
        }

        if ($this->resolvedUser instanceof User) {
            $this->record->user_id = $this->resolvedUser->id;
        }
    }

    private function resolveCustomer(): void
    {
        $customerIdentifier = $this->data['customer_identifier'] ?? null;

        if (empty($customerIdentifier)) {
            throw ValidationException::withMessages([
                'customer_identifier' => 'Customer identifier is required.',
            ]);
        }

        $this->resolvedCustomer = Customer::query()
            ->where('unique_identifier', $customerIdentifier)
            ->orWhere('name', $customerIdentifier)
            ->orWhere('email', $customerIdentifier)
            ->first();

        if ($this->resolvedCustomer === null) {
            throw ValidationException::withMessages([
                'customer_identifier' => 'Customer not found: '.$customerIdentifier,
            ]);
        }
    }

    private function resolveContact(): void
    {
        $contactEmail = $this->data['contact_email'] ?? null;

        if (empty($contactEmail) || ! $this->resolvedCustomer instanceof Customer) {
            return;
        }

        $this->resolvedContact = CustomerContact::query()
            ->where('customer_id', $this->resolvedCustomer->id)
            ->where('email', $contactEmail)
            ->first();
    }

    private function resolveUser(): void
    {
        $userEmail = $this->data['user_email'] ?? null;

        if (empty($userEmail)) {
            throw ValidationException::withMessages([
                'user_email' => 'User email is required.',
            ]);
        }

        $this->resolvedUser = User::query()
            ->where('email', $userEmail)
            ->first();

        if ($this->resolvedUser === null) {
            throw ValidationException::withMessages([
                'user_email' => 'User not found: '.$userEmail,
            ]);
        }
    }

    private function findExistingInteraction(): ?Interaction
    {
        if (! $this->resolvedCustomer instanceof Customer) {
            return null;
        }

        $subject = $this->data['subject'] ?? null;
        $interactionDate = $this->data['interaction_date'] ?? null;

        if (empty($subject) || empty($interactionDate)) {
            return null;
        }

        return Interaction::query()
            ->where('customer_id', $this->resolvedCustomer->id)
            ->where('subject', $subject)
            ->whereDate('interaction_date', $interactionDate)
            ->first();
    }
}
