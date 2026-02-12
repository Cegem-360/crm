<?php

declare(strict_types=1);

namespace App\Filament\Resources\Interactions\Pages;

use App\Filament\Resources\Interactions\InteractionResource;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\EmailTemplate;
use App\Models\Interaction;
use App\Services\EmailService;
use Exception;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Override;

final class CreateInteraction extends CreateRecord
{
    protected static string $resource = InteractionResource::class;

    protected function afterCreate(): void
    {
        /** @var Interaction $interaction */
        $interaction = $this->record;

        if (! $this->shouldSendEmail()) {
            return;
        }

        $template = EmailTemplate::query()->find($this->data['email_template_id']);
        if (! $template) {
            return;
        }

        $recipient = $this->resolveRecipient($interaction);
        if (! $recipient['email']) {
            Notification::make()
                ->warning()
                ->title('Email not sent')
                ->body('No valid email address found for the selected recipient.')
                ->send();

            return;
        }

        try {
            resolve(EmailService::class)->send(
                template: $template,
                recipientEmail: $recipient['email'],
                recipientName: $recipient['name'],
                context: $recipient['context'],
            );

            $interaction->update([
                'email_sent_at' => now(),
                'email_recipient' => $recipient['email'],
            ]);

            Notification::make()
                ->success()
                ->title('Email sent')
                ->body('Email sent successfully to '.$recipient['email'])
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->danger()
                ->title('Email failed')
                ->body('Failed to send email: '.$exception->getMessage())
                ->send();
        }
    }

    #[Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Remove non-model fields
        unset($data['send_email'], $data['recipient_type'], $data['recipient_email']);

        return $data;
    }

    private function shouldSendEmail(): bool
    {
        return ($this->data['send_email'] ?? false)
            && ! empty($this->data['email_template_id']);
    }

    /**
     * Resolve the email recipient based on recipient type.
     *
     * @return array{email: ?string, name: string, context: array<string, mixed>}
     */
    private function resolveRecipient(Interaction $interaction): array
    {
        $context = [];
        $recipientType = $this->data['recipient_type'] ?? 'contact';

        if ($interaction->customer_id) {
            $customer = Customer::with('company')->find($interaction->customer_id);
            $context['customer'] = $customer;
        }

        if ($recipientType === 'contact' && $interaction->customer_contact_id) {
            $contact = CustomerContact::query()->find($interaction->customer_contact_id);
            if ($contact?->email) {
                $context['contact'] = $contact;

                return [
                    'email' => $contact->email,
                    'name' => $contact->name,
                    'context' => $context,
                ];
            }
        }

        if ($recipientType === 'company' && isset($customer) && $customer?->company?->email) {
            $context['company'] = $customer->company;

            return [
                'email' => $customer->company->email,
                'name' => $customer->company->name,
                'context' => $context,
            ];
        }

        return ['email' => null, 'name' => '', 'context' => $context];
    }
}
