<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class TaskInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer.name')
                    ->label(__('Customer'))
                    ->placeholder(__('-')),
                TextEntry::make('assignedUser.name')
                    ->label(__('Assigned To'))
                    ->placeholder(__('-')),
                TextEntry::make('assigner.name')
                    ->label(__('Assigned By'))
                    ->placeholder(__('-')),
                TextEntry::make('title')
                    ->label(__('Title')),
                TextEntry::make('description')
                    ->label(__('Description'))
                    ->placeholder(__('-'))
                    ->columnSpanFull(),
                TextEntry::make('priority')
                    ->label(__('Priority'))
                    ->badge(),
                TextEntry::make('status')
                    ->label(__('Status'))
                    ->badge(),
                TextEntry::make('due_date')
                    ->date()
                    ->placeholder(__('-')),
                TextEntry::make('completed_at')
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder(__('-')),
            ]);
    }
}
