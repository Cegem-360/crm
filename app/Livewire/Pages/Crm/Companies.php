<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm;

use App\Models\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class Companies extends Component
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
        return view('livewire.pages.crm.companies', [
            'companies' => $this->getCompanies(),
        ])->layout('components.layouts.dashboard');
    }

    private function getCompanies(): LengthAwarePaginator
    {
        return Company::query()
            ->withCount('customers')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('tax_number', 'like', "%{$this->search}%")
                        ->orWhere('registration_number', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
