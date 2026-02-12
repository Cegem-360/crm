<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

final class PersonalAccessTokensRelationManager extends RelationManager
{
    protected static string $relationship = 'tokens';

    protected static ?string $title = 'API Tokens';

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Token name'))
                    ->required()
                    ->placeholder('e.g. Workflow Integration'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                TextColumn::make('abilities')
                    ->label(__('Abilities'))
                    ->badge()
                    ->formatStateUsing(function (mixed $state): string {
                        $abilities = is_string($state) ? json_decode($state, true) : $state;

                        return is_array($abilities) ? implode(', ', $abilities) : (string) $state;
                    })
                    ->color('gray'),
                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('last_used_at')
                    ->label(__('Last used'))
                    ->dateTime('Y-m-d H:i')
                    ->placeholder(__('Not yet used'))
                    ->sortable(),
            ])
            ->headerActions([
                Action::make('create')
                    ->label(__('Create new token'))
                    ->icon('heroicon-o-plus')
                    ->form([
                        TextInput::make('name')
                            ->label(__('Token name'))
                            ->required()
                            ->placeholder('e.g. Workflow Integration'),
                        CheckboxList::make('abilities')
                            ->label(__('Abilities'))
                            ->options([
                                'products:read' => __('Read products'),
                                'products:write' => __('Write products'),
                                'products:delete' => __('Delete products'),
                                'customers:read' => __('Read customers'),
                                'customers:write' => __('Write customers'),
                                'orders:read' => __('Read orders'),
                                'orders:write' => __('Write orders'),
                                '*' => __('Full access (all)'),
                            ])
                            ->columns(2)
                            ->helperText(__('Select what this token can access')),
                    ])
                    ->action(function (array $data): void {
                        $abilities = $data['abilities'] ?? ['*'];
                        $token = $this->getOwnerRecord()->createToken($data['name'], $abilities);

                        Notification::make()
                            ->title(__('Token created'))
                            ->body(__('Copy the token, it will not be visible again: ').$token->plainTextToken)
                            ->success()
                            ->persistent()
                            ->send();
                    }),
            ])
            ->actions([
                DeleteAction::make()
                    ->label(__('Revoke')),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->label(__('Revoke selected')),
            ]);
    }
}
