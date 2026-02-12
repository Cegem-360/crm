<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\RelationManagers;

use Override;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class WorkflowConfigsRelationManager extends RelationManager
{
    protected static string $relationship = 'workflowConfigs';

    protected static ?string $title = 'Workflow Konfigurációk';

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Workflow neve')
                    ->required()
                    ->placeholder('pl. Raktárkezelő rendszer'),
                TextInput::make('api_token')
                    ->label('API Token')
                    ->required()
                    ->maxLength(64)
                    ->helperText('Ez a token azonosítja a workflow-t a webhook-okban'),
                TextInput::make('webhook_url')
                    ->label('Webhook URL (opcionális)')
                    ->url()
                    ->placeholder('https://workflow.example.com/api/...'),
                Toggle::make('is_active')
                    ->label('Aktív')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Név')
                    ->searchable(),
                TextColumn::make('api_token')
                    ->label('API Token')
                    ->limit(20)
                    ->copyable()
                    ->tooltip(fn ($record) => $record->api_token),
                IconColumn::make('is_active')
                    ->label('Aktív')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Létrehozva')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Új workflow'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
