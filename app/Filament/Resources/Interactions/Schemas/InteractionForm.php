<?php

declare(strict_types=1);

namespace App\Filament\Resources\Interactions\Schemas;

use App\Enums\InteractionCategory;
use App\Enums\InteractionChannel;
use App\Enums\InteractionDirection;
use App\Enums\InteractionStatus;
use App\Enums\InteractionType;
use App\Models\CustomerContact;
use App\Models\EmailTemplate;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

final class InteractionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->live(),
                Select::make('customer_contact_id')
                    ->label('Contact')
                    ->options(function (Get $get) {
                        $customerId = $get('customer_id');
                        if (! $customerId) {
                            return [];
                        }

                        return CustomerContact::where('customer_id', $customerId)
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->visible(fn (Get $get) => filled($get('customer_id'))),
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->label('Type')
                    ->options(InteractionType::class)
                    ->required()
                    ->default(InteractionType::Note),
                Select::make('category')
                    ->label('Category')
                    ->options(InteractionCategory::class)
                    ->required()
                    ->default(InteractionCategory::General)
                    ->live(),
                Select::make('channel')
                    ->label('Channel')
                    ->options(InteractionChannel::class),
                Select::make('direction')
                    ->label('Direction')
                    ->options(InteractionDirection::class),
                Select::make('status')
                    ->label('Status')
                    ->options(InteractionStatus::class)
                    ->default(InteractionStatus::Completed),
                Select::make('email_template_id')
                    ->label('Email Template')
                    ->options(function (Get $get) {
                        $category = $get('category');

                        return EmailTemplate::query()
                            ->where('is_active', true)
                            ->when($category && $category !== InteractionCategory::General->value, function ($query) use ($category) {
                                $query->where('category', $category);
                            })
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->visible(fn (Get $get) => in_array($get('category'), [
                        InteractionCategory::Sales->value,
                        InteractionCategory::Marketing->value,
                    ])),
                TextInput::make('subject')
                    ->label('Subject')
                    ->required(),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                DateTimePicker::make('interaction_date')
                    ->label('Interaction Date')
                    ->required()
                    ->default(now()),
                TextInput::make('duration')
                    ->label('Duration (minutes)')
                    ->numeric(),
                TextInput::make('next_action')
                    ->label('Next Action'),
                DatePicker::make('next_action_date')
                    ->label('Next Action Date'),
            ]);
    }
}
