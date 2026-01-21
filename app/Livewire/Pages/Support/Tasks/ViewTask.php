<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Support\Tasks;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewTask extends Component
{
    public Task $task;

    public function mount(Task $task): void
    {
        $this->task = $task->load(['customer', 'assignedUser', 'assigner']);
    }

    public function render(): View
    {
        return view('livewire.pages.support.tasks.view-task');
    }
}
