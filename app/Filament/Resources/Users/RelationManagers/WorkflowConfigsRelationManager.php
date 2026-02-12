<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\RelationManagers;

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
use Override;

final class WorkflowConfigsRelationManager extends RelationManager
{
    protected static string $relationship = 'workflowConfigs';

    protected static ?string $title = 'Workflow Configurations';

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Workflow name'))
                    ->required()
                    ->placeholder(__('e.g. Warehouse management system')),
                TextInput::make('api_token')
                    ->label('API Token')
                    ->required()
                    ->maxLength(64)
                    ->helperText(__('This token identifies the workflow in webhooks')),
                TextInput::make('webhook_url')
                    ->label(__('Webhook URL (optional)'))
                    ->url()
                    ->placeholder('https://workflow.example.com/api/...'),
                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                TextColumn::make('api_token')
                    ->label('API Token')
                    ->limit(20)
                    ->copyable()
                    ->tooltip(fn ($record) => $record->api_token),
                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('New workflow')),
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
