<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Interactions;

use App\Enums\InteractionStatus;
use App\Enums\InteractionType;
use App\Models\Interaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class Interactions extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $sortBy = 'interaction_date';

    #[Url]
    public string $sortDir = 'desc';

    #[Url]
    public int $perPage = 10;

    #[Url]
    public string $type = '';

    #[Url]
    public string $status = '';

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

    public function updatedType(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.pages.interactions.interactions', [
            'interactions' => $this->getInteractions(),
            'types' => InteractionType::cases(),
            'statuses' => InteractionStatus::cases(),
        ])->layout('components.layouts.dashboard');
    }

    private function getInteractions(): LengthAwarePaginator
    {
        return Interaction::query()
            ->with(['customer', 'contact', 'user'])
            ->when($this->search !== '', function ($query) {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search) {
                    $q->where('subject', 'like', $search)
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', $search);
                        });
                });
            })
            ->when($this->type !== '', function ($query) {
                $query->where('type', $this->type);
            })
            ->when($this->status !== '', function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
