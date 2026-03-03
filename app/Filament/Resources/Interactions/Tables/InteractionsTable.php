<?php

declare(strict_types=1);

namespace App\Filament\Resources\Interactions\Tables;

use App\Enums\InteractionCategory;
use App\Enums\InteractionChannel;
use App\Enums\InteractionStatus;
use App\Filament\Exports\InteractionExporter;
use App\Filament\Imports\InteractionImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ImportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

final class InteractionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('contact.name')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->badge(),
                TextColumn::make('channel')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('subject')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('interaction_date')
                    ->label(__('Date'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('duration')
                    ->suffix(__(' min'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('next_action')
                    ->label(__('Next Action'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('next_action_date')
                    ->label(__('Next Action Date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options(InteractionCategory::class),
                SelectFilter::make('channel')
                    ->options(InteractionChannel::class),
                SelectFilter::make('status')
                    ->options(InteractionStatus::class),
                SelectFilter::make('customer')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->headerActions([
                ImportAction::make('importInteractions')
                    ->label(__('Import Interactions'))
                    ->pluralModelLabel(__('Interactions'))
                    ->importer(InteractionImporter::class),
                ExportAction::make('exportInteractions')
                    ->label(__('Export Interactions'))
                    ->pluralModelLabel(__('Interactions'))
                    ->exporter(InteractionExporter::class)
                    ->formats([
                        ExportFormat::Xlsx,
                        ExportFormat::Csv,
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('interaction_date', 'desc');
    }
}
