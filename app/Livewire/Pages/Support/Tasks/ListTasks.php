<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Support\Tasks;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ListTasks extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $sortBy = 'created_at';

    #[Url]
    public string $sortDir = 'desc';

    #[Url]
    public int $perPage = 10;

    #[Url]
    public string $status = '';

    #[Url]
    public string $priority = '';

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }

        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedPriority(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.pages.support.tasks.list-tasks', [
            'tasks' => $this->getTasks(),
        ]);
    }

    private function getTasks(): LengthAwarePaginator
    {
        return Task::query()
            ->with(['customer', 'assignedUser', 'assigner'])
            ->when($this->search !== '', function ($query): void {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search): void {
                    $q->where('title', 'like', $search)
                        ->orWhereHas('customer', function ($customerQuery) use ($search): void {
                            $customerQuery->where('name', 'like', $search);
                        });
                });
            })
            ->when($this->status !== '', function ($query): void {
                $query->where('status', $this->status);
            })
            ->when($this->priority !== '', function ($query): void {
                $query->where('priority', $this->priority);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
