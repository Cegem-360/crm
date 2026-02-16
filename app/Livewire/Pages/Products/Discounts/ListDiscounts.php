<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products\Discounts;

use App\Filament\Resources\Discounts\Tables\DiscountsTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Discount;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ListDiscounts extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return DiscountsTable::configure($table)
            ->query(Discount::query())
            ->recordUrl(fn (Discount $record): string => route('dashboard.discounts.view', ['team' => $this->team, 'discount' => $record]))
            ->recordActions([
                Action::make('view')
                    ->url(fn (Discount $record): string => route('dashboard.discounts.view', ['team' => $this->team, 'discount' => $record]))
                    ->icon(Heroicon::Eye),
                Action::make('edit')
                    ->url(fn (Discount $record): string => route('dashboard.discounts.edit', ['team' => $this->team, 'discount' => $record]))
                    ->icon(Heroicon::PencilSquare),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.products.discounts.list-discounts');
    }
}
