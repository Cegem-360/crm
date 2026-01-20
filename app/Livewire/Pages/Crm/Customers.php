<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class Customers extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $sortBy = 'name';

    #[Url]
    public string $sortDir = 'asc';

    #[Url]
    public int $perPage = 10;

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

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $customers = $this->getCustomers();

        return view('livewire.pages.crm.customers', [
            'customers' => $customers,
        ])->layout('components.layouts.dashboard');
    }

    private function getCustomers(): LengthAwarePaginator
    {
        return Customer::query()
            ->with(['company', 'contacts'])
            ->when($this->search !== '', function ($query) {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                        ->orWhere('unique_identifier', 'like', $search)
                        ->orWhere('phone', 'like', $search)
                        ->orWhereHas('company', function ($companyQuery) use ($search) {
                            $companyQuery->where('name', 'like', $search);
                        });
                });
            })
            ->when($this->status !== '', function ($query) {
                if ($this->status === 'active') {
                    $query->where('is_active', true);
                } elseif ($this->status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
