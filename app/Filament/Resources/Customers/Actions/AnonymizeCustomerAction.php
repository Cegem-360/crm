<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Actions;

use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

final class AnonymizeCustomerAction
{
    public static function make(): Action
    {
        return Action::make('anonymize_customer')
            ->label(__('Forget Me (GDPR)'))
            ->icon('heroicon-o-shield-exclamation')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading(__('Anonymize Customer Data'))
            ->modalDescription(__('This action will irreversibly anonymize all personal data for this customer. The customer record will be kept for accounting purposes, but all identifying information will be removed. This cannot be undone.'))
            ->modalSubmitActionLabel(__('Anonymize Data'))
            ->form([
                Textarea::make('reason')
                    ->label(__('Reason for Anonymization'))
                    ->placeholder(__('e.g., Customer requested data deletion per GDPR Art. 17'))
                    ->required(),
            ])
            ->action(function (Customer $record, array $data): void {
                self::anonymize($record, $data['reason']);

                Notification::make()
                    ->success()
                    ->title(__('Customer Data Anonymized'))
                    ->body(__('All personal data for this customer has been anonymized.'))
                    ->send();
            });
    }

    private static function anonymize(Customer $record, string $reason): void
    {
        $anonymizedName = __('Anonymized Customer').' #'.$record->id;

        $record->update([
            'name' => $anonymizedName,
            'email' => null,
            'phone' => null,
            'tax_number' => null,
            'registration_number' => null,
            'notes' => __('Data anonymized on :date. Reason: :reason', [
                'date' => now()->toDateString(),
                'reason' => $reason,
            ]),
            'is_active' => false,
        ]);

        $record->contacts()->delete();
        $record->addresses()->delete();

        $record->consents()->update([
            'is_granted' => false,
            'revoked_at' => now(),
            'ip_address' => null,
            'notes' => __('Revoked due to data anonymization'),
        ]);

        activity()
            ->performedOn($record)
            ->causedBy(Auth::user())
            ->withProperties(['reason' => $reason])
            ->log('customer_data_anonymized');
    }
}
