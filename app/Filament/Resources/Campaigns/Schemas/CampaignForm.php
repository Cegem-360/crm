<?php

declare(strict_types=1);

namespace App\Filament\Resources\Campaigns\Schemas;

use App\Enums\CampaignStatus;
use App\Enums\Role;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

final class CampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('name')
                    ->required()
                    ->placeholder(__('e.g., Q1 2024 Marketing Initiative')),
                Textarea::make('description')
                    ->placeholder(__('Campaign goals and details...'))
                    ->columnSpanFull(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date'),
                Select::make('status')
                    ->required()
                    ->options(CampaignStatus::class)
                    ->enum(CampaignStatus::class)
                    ->default(CampaignStatus::Draft),
                Textarea::make('target_audience_criteria')
                    ->placeholder(__('Define who this campaign targets...'))
                    ->helperText(__('Describe the target audience segment'))
                    ->columnSpanFull(),
                Select::make('created_by')
                    ->relationship('creator', 'name')
                    ->default(static fn (): ?int => Auth::id())
                    ->visible(static fn (): bool => Auth::user()?->hasRole(Role::Admin) ?? false)
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }
}
