<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeadOpportunities\Schemas;

use App\Models\Opportunity;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class OpportunityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('customer.name')
                    ->label(__('Customer')),
                TextEntry::make('value')
                    ->numeric()
                    ->placeholder(__('-')),
                TextEntry::make('probability')
                    ->suffix('%'),
                TextEntry::make('stage'),
                TextEntry::make('expected_close_date')
                    ->date()
                    ->placeholder(__('-')),
                TextEntry::make('assignedUser.name')
                    ->label(__('Assigned To'))
                    ->placeholder(__('-')),
                TextEntry::make('description')
                    ->placeholder(__('-'))
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Opportunity $record): bool => $record->trashed()),
            ]);
    }
}
