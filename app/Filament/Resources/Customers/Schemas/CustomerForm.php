<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Schemas;

use App\Enums\CustomerType;
use App\Enums\CustomFieldModel;
use App\Filament\Concerns\HasCustomFieldsSchema;
use App\Models\Customer;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Collection;

final class CustomerForm
{
    use HasCustomFieldsSchema;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Callout::make(__('Potential duplicates found'))
                    ->description(static function (Get $get, ?Customer $record): ?string {
                        $duplicates = self::findDuplicates($get, $record);

                        if ($duplicates->isEmpty()) {
                            return null;
                        }

                        return $duplicates
                            ->map(static fn (Customer $customer): string => sprintf('• %s (%s)', $customer->name, $customer->unique_identifier)
                                .(filled($customer->email) ? ' — '.$customer->email : '')
                                .(filled($customer->phone) ? ' — '.$customer->phone : ''))
                            ->implode("\n");
                    })
                    ->warning()
                    ->icon('heroicon-o-exclamation-triangle')
                    ->visible(static fn (Get $get, ?Customer $record): bool => self::findDuplicates($get, $record)->isNotEmpty())
                    ->columnSpanFull(),
                TextInput::make('unique_identifier')
                    ->disabled(fn (?Customer $record): bool => $record?->exists === true)
                    ->dehydrated()
                    ->placeholder(__('Auto-generated if empty'))
                    ->scopedUnique(ignoreRecord: true),
                TextInput::make('name')
                    ->placeholder(__('e.g., John Doe or Company Name'))
                    ->required()
                    ->live(onBlur: true),
                Select::make('type')
                    ->required()
                    ->options(CustomerType::class)
                    ->default(CustomerType::Individual)
                    ->live(),
                TextInput::make('phone')
                    ->tel()
                    ->placeholder(__('+36 XX XXX XXXX'))
                    ->live(onBlur: true),
                TextInput::make('email')
                    ->label(__('E-mail'))
                    ->email()
                    ->placeholder(__('email@example.com'))
                    ->live(onBlur: true),
                Section::make(__('Company information'))
                    ->visible(fn (Get $get): bool => $get('type') === CustomerType::Company)
                    ->schema([
                        TextInput::make('tax_number')
                            ->placeholder(__('e.g., 12345678-1-23'))
                            ->live(onBlur: true),
                        TextInput::make('registration_number')
                            ->placeholder(__('e.g., 01-09-123456')),
                    ])
                    ->columns(2),
                Textarea::make('notes')
                    ->placeholder(__('Additional information about the customer...'))
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->required(),

                Section::make(__('Contacts'))
                    ->schema([
                        Repeater::make('contacts')
                            ->label('')
                            ->relationship()
                            ->schema([
                                TextInput::make('name')
                                    ->required(),
                                TextInput::make('email')
                                    ->label(__('Email address'))
                                    ->email(),
                                TextInput::make('phone')
                                    ->tel(),
                                TextInput::make('position'),
                                Toggle::make('is_primary')
                                    ->default(false),
                            ])
                            ->columns(5)
                            ->defaultItems(0)
                            ->addActionLabel(__('Add Contact'))
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Addresses'))
                    ->schema([
                        Repeater::make('addresses')
                            ->label('')
                            ->relationship()
                            ->schema([
                                Select::make('type')
                                    ->required()
                                    ->default('billing')
                                    ->options([
                                        'billing' => __('Billing'),
                                        'shipping' => __('Shipping'),
                                    ]),
                                TextInput::make('country')
                                    ->required(),
                                TextInput::make('postal_code')
                                    ->required(),
                                TextInput::make('city')
                                    ->required(),
                                TextInput::make('street')
                                    ->required(),
                                TextInput::make('building_number'),
                                TextInput::make('floor'),
                                TextInput::make('door'),
                                Toggle::make('is_default')
                                    ->default(false),
                            ])
                            ->columns(4)
                            ->defaultItems(0)
                            ->addActionLabel(__('Add Address'))
                            ->itemLabel(fn (array $state): ?string => implode(' ', array_filter([
                                $state['postal_code'] ?? null,
                                $state['city'] ?? null,
                                $state['street'] ?? null,
                                $state['building_number'] ?? null,
                            ])) ?: null)
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                ...self::getCustomFieldsFormSection(CustomFieldModel::Customer),
            ]);
    }

    /**
     * @return Collection<int, Customer>
     */
    private static function findDuplicates(Get $get, ?Customer $record): Collection
    {
        $name = $get('name');
        $email = $get('email');
        $phone = $get('phone');
        $taxNumber = $get('tax_number');

        if (blank($name) && blank($email) && blank($phone) && blank($taxNumber)) {
            return new Collection;
        }

        return Customer::query()
            ->when($record?->exists, static fn ($query) => $query->where('id', '!=', $record->id))
            ->where(static function ($query) use ($name, $email, $phone, $taxNumber): void {
                if (filled($name) && mb_strlen((string) $name) >= 3) {
                    $query->orWhere('name', 'like', sprintf('%%%s%%', $name));
                }

                if (filled($email)) {
                    $query->orWhere('email', $email);
                }

                if (filled($phone)) {
                    $query->orWhere('phone', $phone);
                }

                if (filled($taxNumber)) {
                    $query->orWhere('tax_number', $taxNumber);
                }
            })
            ->limit(5)
            ->get();
    }
}
