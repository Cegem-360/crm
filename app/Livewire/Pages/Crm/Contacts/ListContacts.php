<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm\Contacts;

use App\Filament\Resources\Contacts\Tables\ContactsTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\CustomerContact;
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
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ListContacts extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return ContactsTable::configure($table)
            ->query(CustomerContact::query())
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['customer']))
            ->recordUrl(fn (CustomerContact $record): string => route('dashboard.contacts.view', ['team' => $this->team, 'contact' => $record]))
            ->recordActions([
                Action::make('view')
                    ->url(fn (CustomerContact $record): string => route('dashboard.contacts.view', ['team' => $this->team, 'contact' => $record]))
                    ->icon(Heroicon::Eye),
                Action::make('edit')
                    ->url(fn (CustomerContact $record): string => route('dashboard.contacts.edit', ['team' => $this->team, 'contact' => $record]))
                    ->icon(Heroicon::PencilSquare),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.crm.contacts.list-contacts');
    }
}
