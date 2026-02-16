<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Support\Complaints;

use App\Filament\Resources\Complaints\Tables\ComplaintsTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Complaint;
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
final class ListComplaints extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return ComplaintsTable::configure($table)
            ->query(Complaint::query())
            ->recordUrl(fn (Complaint $record): string => route('dashboard.complaints.view', ['team' => $this->team, 'complaint' => $record]))
            ->recordActions([
                Action::make('view')
                    ->url(fn (Complaint $record): string => route('dashboard.complaints.view', ['team' => $this->team, 'complaint' => $record]))
                    ->icon(Heroicon::Eye),
                Action::make('edit')
                    ->url(fn (Complaint $record): string => route('dashboard.complaints.edit', ['team' => $this->team, 'complaint' => $record]))
                    ->icon(Heroicon::PencilSquare),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.support.complaints.list-complaints');
    }
}
