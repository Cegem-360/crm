<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm\Companies;

use App\Models\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ListCompanies extends Component
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

    #[On('company-saved')]
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
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.pages.crm.companies.list-companies', [
            'companies' => $this->getCompanies(),
        ]);
    }

    private function getCompanies(): LengthAwarePaginator
    {
        return Company::query()
            ->withCount('customers')
            ->when($this->search, function ($query): void {
                $query->where(function ($q): void {
                    $q->where('name', 'like', sprintf('%%%s%%', $this->search))
                        ->orWhere('email', 'like', sprintf('%%%s%%', $this->search))
                        ->orWhere('tax_number', 'like', sprintf('%%%s%%', $this->search))
                        ->orWhere('registration_number', 'like', sprintf('%%%s%%', $this->search));
                });
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
