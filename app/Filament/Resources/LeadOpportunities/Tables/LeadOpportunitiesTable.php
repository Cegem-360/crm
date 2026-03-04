<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeadOpportunities\Tables;

use App\Enums\OpportunityStage;
use App\Models\TeamSetting;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class LeadOpportunitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Opportunity Title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label(__('Customer Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stage')
                    ->label(__('Stage'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('value')
                    ->money(TeamSetting::currentCurrency())
                    ->sortable(),
                TextColumn::make('probability')
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('expected_close_date')
                    ->label(__('Expected Close Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('assignedUser.name')
                    ->label(__('Assigned To'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('stage')
                    ->label(__('Stage'))
                    ->options(OpportunityStage::class),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
