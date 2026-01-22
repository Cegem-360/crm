<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Support\Tasks;

use App\Filament\Resources\Tasks\Schemas\TaskForm;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Task;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditTask extends Component implements HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithSchemas;

    public ?Task $task = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?Task $task = null): void
    {
        $this->task = $task;
        $this->form->fill($task?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return TaskForm::configure($schema)
            ->statePath('data')
            ->model($this->task ?? Task::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->task?->exists) {
            $this->task->update($data);
            $message = __('Task updated successfully.');
        } else {
            $this->task = Task::create(array_merge($data, ['team_id' => $this->team->id]));
            $this->form->model($this->task)->saveRelationships();
            $message = __('Task created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.tasks.view', ['team' => $this->team, 'task' => $this->task]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.support.tasks.edit-task');
    }
}
