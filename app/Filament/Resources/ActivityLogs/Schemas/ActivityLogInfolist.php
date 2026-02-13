<?php

declare(strict_types=1);

namespace App\Filament\Resources\ActivityLogs\Schemas;

use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class ActivityLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Activity Details'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('log_name')
                                    ->label(__('Log Name'))
                                    ->badge(),
                                TextEntry::make('event')
                                    ->label(__('Event'))
                                    ->badge()
                                    ->colors([
                                        'success' => 'created',
                                        'warning' => 'updated',
                                        'danger' => 'deleted',
                                    ]),
                                TextEntry::make('description')
                                    ->label(__('Description'))
                                    ->columnSpanFull(),
                            ]),
                    ]),
                Section::make(__('Subject Information'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('subject_type')
                                    ->label(__('Subject Type'))
                                    ->formatStateUsing(fn ($state): string => $state ? class_basename($state) : 'N/A'),
                                TextEntry::make('subject_id')
                                    ->label(__('Subject ID')),
                            ]),
                    ]),
                Section::make(__('Causer Information'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('causer_type')
                                    ->label(__('Causer Type'))
                                    ->formatStateUsing(fn ($state): string => $state ? class_basename($state) : 'System'),
                                TextEntry::make('causer_id')
                                    ->label(__('Causer ID'))
                                    ->placeholder(__('System')),
                                TextEntry::make('causer.name')
                                    ->label(__('User Name'))
                                    ->placeholder(__('System')),
                                TextEntry::make('causer.email')
                                    ->label(__('User Email'))
                                    ->placeholder(__('System')),
                            ]),
                    ]),
                Section::make(__('Changes'))
                    ->schema([
                        KeyValueEntry::make('properties.attributes')
                            ->label(__('New Values'))
                            ->columnSpanFull()
                            ->visible(fn ($record): bool => ! empty($record->properties['attributes'] ?? [])),
                        KeyValueEntry::make('properties.old')
                            ->label(__('Old Values'))
                            ->columnSpanFull()
                            ->visible(fn ($record): bool => ! empty($record->properties['old'] ?? [])),
                    ])
                    ->collapsed(),
                Section::make(__('Metadata'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('batch_uuid')
                                    ->label(__('Batch UUID'))
                                    ->placeholder(__('N/A')),
                                TextEntry::make('created_at')
                                    ->label(__('Created At'))
                                    ->dateTime(),
                            ]),
                    ])
                    ->collapsed(),
            ]);
    }
}
