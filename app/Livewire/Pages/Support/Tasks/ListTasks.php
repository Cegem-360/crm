<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Support\Tasks;

use App\Filament\Resources\Tasks\Tables\TasksTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Task;
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
final class ListTasks extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return TasksTable::configure($table)
            ->query(Task::query())
            ->recordUrl(fn (Task $record): string => route('dashboard.tasks.view', ['team' => $this->team, 'task' => $record]))
            ->recordActions([
                Action::make('view')
                    ->url(fn (Task $record): string => route('dashboard.tasks.view', ['team' => $this->team, 'task' => $record]))
                    ->icon(Heroicon::Eye),
                Action::make('edit')
                    ->url(fn (Task $record): string => route('dashboard.tasks.edit', ['team' => $this->team, 'task' => $record]))
                    ->icon(Heroicon::PencilSquare),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.support.tasks.list-tasks');
    }
}
