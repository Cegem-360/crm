<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Actions;

use App\Models\Customer;
use Filament\Actions\Action;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ExportCustomerDataAction
{
    public static function make(): Action
    {
        return Action::make('export_customer_data')
            ->label(__('Export Data (GDPR)'))
            ->icon('heroicon-o-arrow-down-tray')
            ->color('info')
            ->requiresConfirmation()
            ->modalHeading(__('Export Customer Data'))
            ->modalDescription(__('This will export all personal data stored for this customer in JSON format, in accordance with GDPR data portability rights.'))
            ->modalSubmitActionLabel(__('Export'))
            ->action(function (Customer $record): StreamedResponse {
                $data = self::collectCustomerData($record);
                $filename = 'gdpr-export-'.$record->unique_identifier.'-'.now()->format('Y-m-d').'.json';

                return response()->streamDownload(function () use ($data): void {
                    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }, $filename, [
                    'Content-Type' => 'application/json',
                ]);
            });
    }

    /**
     * @return array<string, mixed>
     */
    private static function collectCustomerData(Customer $record): array
    {
        $record->load([
            'contacts',
            'addresses',
            'consents',
            'interactions',
            'quotes',
            'orders',
            'invoices',
        ]);

        return [
            'export_date' => now()->toIso8601String(),
            'customer' => [
                'identifier' => $record->unique_identifier,
                'name' => $record->name,
                'type' => $record->type?->value,
                'email' => $record->email,
                'phone' => $record->phone,
                'tax_number' => $record->tax_number,
                'registration_number' => $record->registration_number,
                'notes' => $record->notes,
                'is_active' => $record->is_active,
                'created_at' => $record->created_at?->toIso8601String(),
            ],
            'contacts' => $record->contacts->map(fn ($contact): array => [
                'name' => $contact->name,
                'email' => $contact->email,
                'phone' => $contact->phone,
                'position' => $contact->position,
                'is_primary' => $contact->is_primary,
            ])->toArray(),
            'addresses' => $record->addresses->map(fn ($address): array => [
                'type' => $address->type,
                'street' => $address->street,
                'city' => $address->city,
                'state' => $address->state,
                'postal_code' => $address->postal_code,
                'country' => $address->country,
            ])->toArray(),
            'consents' => $record->consents->map(fn ($consent): array => [
                'type' => $consent->type?->value,
                'is_granted' => $consent->is_granted,
                'granted_at' => $consent->granted_at?->toIso8601String(),
                'revoked_at' => $consent->revoked_at?->toIso8601String(),
            ])->toArray(),
            'interactions' => $record->interactions->map(fn ($interaction): array => [
                'type' => $interaction->type?->value,
                'channel' => $interaction->channel?->value,
                'subject' => $interaction->subject,
                'created_at' => $interaction->created_at?->toIso8601String(),
            ])->toArray(),
            'quotes_count' => $record->quotes->count(),
            'orders_count' => $record->orders->count(),
            'invoices_count' => $record->invoices->count(),
        ];
    }
}
