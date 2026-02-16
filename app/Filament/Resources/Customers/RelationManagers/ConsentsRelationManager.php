<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Enums\ConsentType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Override;

final class ConsentsRelationManager extends RelationManager
{
    protected static string $relationship = 'consents';

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label(__('Consent Type'))
                    ->required()
                    ->enum(ConsentType::class)
                    ->options(ConsentType::class),
                Toggle::make('is_granted')
                    ->label(__('Granted'))
                    ->live()
                    ->afterStateUpdated(function (bool $state, callable $set): void {
                        if ($state) {
                            $set('granted_at', now()->toDateTimeString());
                            $set('revoked_at', null);
                        } else {
                            $set('revoked_at', now()->toDateTimeString());
                        }
                    }),
                DateTimePicker::make('granted_at')
                    ->label(__('Granted At')),
                DateTimePicker::make('revoked_at')
                    ->label(__('Revoked At')),
                Textarea::make('notes')
                    ->label(__('Notes'))
                    ->placeholder(__('Reason for consent change, source of consent...'))
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('type')
                    ->label(__('Consent Type'))
                    ->badge()
                    ->sortable(),
                IconColumn::make('is_granted')
                    ->label(__('Granted'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('granted_at')
                    ->label(__('Granted At'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('revoked_at')
                    ->label(__('Revoked At'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('grantedByUser.name')
                    ->label(__('Granted By'))
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('Last Updated'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('Consent Type'))
                    ->options(ConsentType::class),
                TernaryFilter::make('is_granted')
                    ->label(__('Status'))
                    ->trueLabel(__('Granted'))
                    ->falseLabel(__('Revoked'))
                    ->placeholder(__('All')),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['granted_by'] = Auth::id();
                        $data['ip_address'] = request()->ip();

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['granted_by'] = Auth::id();

                        return $data;
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
