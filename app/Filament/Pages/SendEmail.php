<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\EmailTemplate;
use App\Services\EmailService;
use Exception;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Override;
use UnitEnum;

final class SendEmail extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    /** @var array<string, mixed> */
    public array $data = [];

    protected string $view = 'filament.pages.send-email';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Interactions;

    protected static ?int $navigationSort = 3;

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Send Email');
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    #[Override]
    public function getTitle(): string
    {
        return __('Send Email');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('email_template_id')
                    ->label(__('Email Template'))
                    ->options(
                        EmailTemplate::query()
                            ->where('is_active', true)
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (?string $state, Set $set): void {
                        if ($state) {
                            $template = EmailTemplate::query()->find($state);
                            if ($template) {
                                $set('preview_subject', $template->subject);
                            }
                        }
                    }),

                Select::make('recipient_type')
                    ->label(__('Send to'))
                    ->options([
                        'contact' => __('Contact'),
                        'company' => __('Company'),
                    ])
                    ->default('contact')
                    ->required()
                    ->live(),

                Select::make('customer_id')
                    ->label(__('Customer'))
                    ->options(Customer::query()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->visible(fn (Get $get): bool => $get('recipient_type') === 'contact'),

                Select::make('contact_id')
                    ->label(__('Contact'))
                    ->options(function (Get $get) {
                        $customerId = $get('customer_id');
                        if (! $customerId) {
                            return CustomerContact::query()->pluck('name', 'id');
                        }

                        return CustomerContact::query()
                            ->where('customer_id', $customerId)
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->visible(fn (Get $get): bool => $get('recipient_type') === 'contact')
                    ->afterStateUpdated(function (?string $state, Set $set): void {
                        if ($state) {
                            $contact = CustomerContact::query()->find($state);
                            if ($contact?->email) {
                                $set('recipient_email', $contact->email);
                            }
                        }
                    }),

                Select::make('company_id')
                    ->label(__('Company'))
                    ->options(
                        Company::query()
                            ->whereNotNull('email')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->visible(fn (Get $get): bool => $get('recipient_type') === 'company')
                    ->afterStateUpdated(function (?string $state, Set $set): void {
                        if ($state) {
                            $company = Company::query()->find($state);
                            if ($company?->email) {
                                $set('recipient_email', $company->email);
                            }
                        }
                    }),

                TextInput::make('recipient_email')
                    ->label(__('Recipient Email'))
                    ->email()
                    ->required()
                    ->disabled(),

                TextInput::make('preview_subject')
                    ->label(__('Subject Preview'))
                    ->disabled()
                    ->dehydrated(false),
            ])
            ->statePath('data');
    }

    public function send(): void
    {
        $data = $this->form->getState();

        $template = EmailTemplate::query()->find($data['email_template_id']);
        if (! $template) {
            $this->sendErrorNotification(__('Email template not found.'));

            return;
        }

        $recipientEmail = $data['recipient_email'] ?? null;
        if (! $recipientEmail) {
            $this->sendErrorNotification(__('No recipient email address.'));

            return;
        }

        $recipient = $this->resolveRecipient($data);

        try {
            resolve(EmailService::class)->send(
                template: $template,
                recipientEmail: $recipientEmail,
                recipientName: $recipient['name'],
                context: $recipient['context'],
            );

            Notification::make()
                ->success()
                ->title(__('Email sent'))
                ->body(__('Email sent successfully to :email', ['email' => $recipientEmail]))
                ->send();

            $this->form->fill();
        } catch (Exception $exception) {
            Notification::make()
                ->danger()
                ->title(__('Email failed'))
                ->body(__('Failed to send email: :message', ['message' => $exception->getMessage()]))
                ->send();
        }
    }

    /**
     * Resolve recipient details based on recipient type.
     *
     * @param  array<string, mixed>  $data
     * @return array{name: string, context: array<string, mixed>}
     */
    private function resolveRecipient(array $data): array
    {
        if ($data['recipient_type'] === 'contact' && isset($data['contact_id'])) {
            $contact = CustomerContact::with('customer.company')->find($data['contact_id']);
            if ($contact) {
                return [
                    'name' => $contact->name,
                    'context' => [
                        'contact' => $contact,
                        'customer' => $contact->customer,
                        'company' => $contact->customer?->company,
                    ],
                ];
            }
        }

        if ($data['recipient_type'] === 'company' && isset($data['company_id'])) {
            $company = Company::query()->find($data['company_id']);
            if ($company) {
                return [
                    'name' => $company->name,
                    'context' => ['company' => $company],
                ];
            }
        }

        return ['name' => '', 'context' => []];
    }

    private function sendErrorNotification(string $message): void
    {
        Notification::make()
            ->danger()
            ->title(__('Error'))
            ->body($message)
            ->send();
    }
}
