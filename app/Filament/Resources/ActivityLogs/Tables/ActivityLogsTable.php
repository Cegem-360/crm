<?php

declare(strict_types=1);

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

final class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('log_name')
                    ->label(__('Log Name'))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label(__('Event'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject_type')
                    ->label(__('Subject Type'))
                    ->searchable()
                    ->formatStateUsing(fn ($state): string => class_basename($state))
                    ->sortable(),
                TextColumn::make('subject_id')
                    ->label(__('Subject ID'))
                    ->sortable(),
                TextColumn::make('causer.name')
                    ->label(__('User'))
                    ->searchable()
                    ->sortable()
                    ->placeholder(__('System')),
                TextColumn::make('event')
                    ->label(__('Action'))
                    ->badge()
                    ->colors([
                        'success' => 'created',
                        'warning' => 'updated',
                        'danger' => 'deleted',
                    ])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Date'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->label(__('Log Name'))
                    ->options(fn () => Activity::query()
                        ->distinct()
                        ->pluck('log_name', 'log_name')
                        ->toArray()
                    ),
                SelectFilter::make('event')
                    ->label(__('Event'))
                    ->options([
                        'created' => __('Created'),
                        'updated' => __('Updated'),
                        'deleted' => __('Deleted'),
                    ]),
                SelectFilter::make('subject_type')
                    ->label(__('Subject Type'))
                    ->options(fn () => Activity::query()
                        ->distinct()
                        ->pluck('subject_type', 'subject_type')
                        ->mapWithKeys(fn ($item): array => [$item => class_basename($item)])
                        ->all()
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
