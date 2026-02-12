<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm\Customers;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ListCustomers extends Component
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

    #[On('customer-saved')]
    public function refreshList(): void
    {
        // The list will be refreshed automatically
    }

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
        return view('livewire.pages.crm.customers.list-customers', [
            'customers' => $this->getCustomers(),
        ]);
    }

    private function getCustomers(): LengthAwarePaginator
    {
        return Customer::query()
            ->with(['company', 'contacts'])
            ->when($this->search !== '', function ($query): void {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search): void {
                    $q->where('name', 'like', $search)
                        ->orWhere('unique_identifier', 'like', $search)
                        ->orWhere('phone', 'like', $search)
                        ->orWhereHas('company', function ($companyQuery) use ($search): void {
                            $companyQuery->where('name', 'like', $search);
                        });
                });
            })
            ->when($this->status !== '', function ($query): void {
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
