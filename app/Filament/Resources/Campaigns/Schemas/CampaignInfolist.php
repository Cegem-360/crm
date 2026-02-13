<?php

declare(strict_types=1);

namespace App\Filament\Resources\Campaigns\Schemas;

use App\Models\Campaign;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class CampaignInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder(__('-'))
                    ->columnSpanFull(),
                TextEntry::make('start_date')
                    ->date(),
                TextEntry::make('end_date')
                    ->date()
                    ->placeholder(__('-')),
                TextEntry::make('status'),
                TextEntry::make('target_audience_criteria')
                    ->placeholder(__('-'))
                    ->columnSpanFull(),
                TextEntry::make('created_by')
                    ->numeric()
                    ->placeholder(__('-')),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Campaign $record): bool => $record->trashed()),
            ]);
    }
}
