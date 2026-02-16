<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Support\Tasks;

use App\Filament\Resources\Tasks\Schemas\TaskInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Task;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewTask extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Task $task;

    public function mount(Task $task): void
    {
        $this->task = $task;
    }

    public function infolist(Schema $schema): Schema
    {
        return TaskInfolist::configure($schema)
            ->record($this->task)
            ->columns(2);
    }

    public function render(): View
    {
        return view('livewire.pages.support.tasks.view-task');
    }
}
