<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Opportunities;

use App\Filament\Resources\Customers\Actions\GenerateQuoteAction;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Opportunity;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ListOpportunities extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Opportunity::query())
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['customer', 'assignedUser']))
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('value')
                    ->label(__('Value'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('probability')
                    ->label(__('Probability'))
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('stage')
                    ->label(__('Stage'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('expected_close_date')
                    ->label(__('Expected Close'))
                    ->date()
                    ->sortable(),
                TextColumn::make('assignedUser.name')
                    ->label(__('Assigned To'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(fn (Opportunity $record): string => route('dashboard.opportunities.view', ['team' => $this->team, 'opportunity' => $record]))
            ->recordActions([
                GenerateQuoteAction::make(),
                Action::make('view')
                    ->url(fn (Opportunity $record): string => route('dashboard.opportunities.view', ['team' => $this->team, 'opportunity' => $record]))
                    ->icon(Heroicon::Eye),
                Action::make('edit')
                    ->url(fn (Opportunity $record): string => route('dashboard.opportunities.edit', ['team' => $this->team, 'opportunity' => $record]))
                    ->icon(Heroicon::PencilSquare),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.opportunities.list-opportunities');
    }
}
