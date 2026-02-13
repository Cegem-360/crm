<?php

declare(strict_types=1);

namespace App\Filament\Resources\Interactions\Tables;

use App\Enums\InteractionCategory;
use App\Enums\InteractionChannel;
use App\Enums\InteractionStatus;
use App\Enums\InteractionType;
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
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('contact.name')
                    ->label(__('Contact'))
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('user.name')
                    ->label(__('User'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge(),
                TextColumn::make('category')
                    ->label(__('Category'))
                    ->badge(),
                TextColumn::make('channel')
                    ->label(__('Channel'))
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
                TextColumn::make('subject')
                    ->label(__('Subject'))
                    ->searchable()
                    ->limit(30),
                TextColumn::make('interaction_date')
                    ->label(__('Date'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('duration')
                    ->label(__('Duration'))
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
                SelectFilter::make('type')
                    ->options(InteractionType::class),
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
                ImportAction::make(__('Import Interactions'))
                    ->importer(InteractionImporter::class),
                ExportAction::make(__('Export Interactions'))
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
