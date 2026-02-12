<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Opportunities;

use App\Enums\OpportunityStage;
use App\Models\Opportunity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ListOpportunities extends Component
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
    public string $stage = '';

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

    public function updatedStage(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.pages.sales.opportunities.list-opportunities', [
            'opportunities' => $this->getOpportunities(),
            'stages' => OpportunityStage::cases(),
        ]);
    }

    private function getOpportunities(): LengthAwarePaginator
    {
        return Opportunity::query()
            ->with(['customer', 'assignedUser'])
            ->when($this->search !== '', function ($query): void {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search): void {
                    $q->where('title', 'like', $search)
                        ->orWhereHas('customer', function ($customerQuery) use ($search): void {
                            $customerQuery->where('name', 'like', $search);
                        });
                });
            })
            ->when($this->stage !== '', function ($query): void {
                $query->where('stage', $this->stage);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
