<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Schemas;

use App\Enums\CustomerType;
use App\Enums\CustomFieldModel;
use App\Filament\Concerns\HasCustomFieldsSchema;
use App\Models\Customer;
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
                    ->label(__('Unique Identifier'))
                    ->disabled()
                    ->dehydrated()
                    ->placeholder(__('Auto-generated')),
                TextInput::make('name')
                    ->label(__('Name'))
                    ->placeholder(__('e.g., John Doe or Company Name'))
                    ->required()
                    ->live(onBlur: true),
                Select::make('type')
                    ->label(__('Type'))
                    ->required()
                    ->options(CustomerType::class)
                    ->default(CustomerType::Individual)
                    ->live(),
                TextInput::make('phone')
                    ->label(__('Phone'))
                    ->tel()
                    ->placeholder(__('+36 XX XXX XXXX'))
                    ->live(onBlur: true),
                TextInput::make('email')
                    ->label(__('E-mail'))
                    ->email()
                    ->placeholder(__('email@example.com'))
                    ->live(onBlur: true),
                Section::make(__('Company information'))
                    ->visible(fn (Get $get): bool => $get('type') === CustomerType::Company->value)
                    ->schema([
                        TextInput::make('tax_number')
                            ->label(__('Tax number'))
                            ->placeholder(__('e.g., 12345678-1-23'))
                            ->live(onBlur: true),
                        TextInput::make('registration_number')
                            ->label(__('Registration number'))
                            ->placeholder(__('e.g., 01-09-123456')),
                    ])
                    ->columns(2),
                Textarea::make('notes')
                    ->label(__('Notes'))
                    ->placeholder(__('Additional information about the customer...'))
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->required(),
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
