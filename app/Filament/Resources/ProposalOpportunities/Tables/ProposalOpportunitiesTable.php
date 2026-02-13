<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProposalOpportunities\Tables;

use App\Enums\OpportunityStage;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class ProposalOpportunitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->where('stage', OpportunityStage::Proposal))
            ->columns([
                TextColumn::make('customer.name')
                    ->label(__('Customer Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.email')
                    ->label(__('Customer Email'))
                    ->searchable(),
                TextColumn::make('customer.phone')
                    ->label(__('Customer Phone'))
                    ->searchable(),
                TextColumn::make('customer.type')
                    ->label(__('Customer Type'))
                    ->badge(),
                TextColumn::make('title')
                    ->label(__('Opportunity Title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('value')
                    ->label(__('Value'))
                    ->money('HUF')
                    ->sortable(),
                TextColumn::make('probability')
                    ->label(__('Probability'))
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
            ]);
    }
}
