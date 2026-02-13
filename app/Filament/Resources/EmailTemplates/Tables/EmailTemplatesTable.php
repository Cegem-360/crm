<?php

declare(strict_types=1);

namespace App\Filament\Resources\EmailTemplates\Tables;

use App\Enums\EmailTemplateCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class EmailTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject')
                    ->label(__('Subject'))
                    ->searchable()
                    ->limit(40),
                TextColumn::make('category')
                    ->label(__('Category'))
                    ->badge(),
                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
                TextColumn::make('createdBy.name')
                    ->label(__('Created By'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options(EmailTemplateCategory::class),
                SelectFilter::make('is_active')
                    ->options([
                        true => __('Active'),
                        false => __('Inactive'),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
