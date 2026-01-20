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

final class PersonalAccessTokensRelationManager extends RelationManager
{
    protected static string $relationship = 'tokens';

    protected static ?string $title = 'API Tokenek';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Token neve')
                    ->required()
                    ->placeholder('pl. Workflow Integration'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Név')
                    ->searchable(),
                TextColumn::make('abilities')
                    ->label('Jogosultságok')
                    ->badge()
                    ->formatStateUsing(function (mixed $state): string {
                        $abilities = is_string($state) ? json_decode($state, true) : $state;

                        return is_array($abilities) ? implode(', ', $abilities) : (string) $state;
                    })
                    ->color('gray'),
                TextColumn::make('created_at')
                    ->label('Létrehozva')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('last_used_at')
                    ->label('Utoljára használva')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('Még nem használt')
                    ->sortable(),
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Új token létrehozása')
                    ->icon('heroicon-o-plus')
                    ->form([
                        TextInput::make('name')
                            ->label('Token neve')
                            ->required()
                            ->placeholder('pl. Workflow Integration'),
                        CheckboxList::make('abilities')
                            ->label('Jogosultságok')
                            ->options([
                                'products:read' => 'Termékek olvasása',
                                'products:write' => 'Termékek írása',
                                'products:delete' => 'Termékek törlése',
                                'customers:read' => 'Ügyfelek olvasása',
                                'customers:write' => 'Ügyfelek írása',
                                'orders:read' => 'Rendelések olvasása',
                                'orders:write' => 'Rendelések írása',
                                '*' => 'Teljes hozzáférés (minden)',
                            ])
                            ->columns(2)
                            ->helperText('Válaszd ki, mihez férhet hozzá ez a token'),
                    ])
                    ->action(function (array $data): void {
                        $abilities = $data['abilities'] ?? ['*'];
                        $token = $this->getOwnerRecord()->createToken($data['name'], $abilities);

                        Notification::make()
                            ->title('Token létrehozva')
                            ->body('Másold ki a tokent, mert többé nem lesz látható: '.$token->plainTextToken)
                            ->success()
                            ->persistent()
                            ->send();
                    }),
            ])
            ->actions([
                DeleteAction::make()
                    ->label('Visszavonás'),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->label('Kijelöltek visszavonása'),
            ]);
    }
}
