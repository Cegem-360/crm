<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm;

use App\Models\CustomerContact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class Contacts extends Component
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
    public string $primary = '';

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

    public function updatedPrimary(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.pages.crm.contacts', [
            'contacts' => $this->getContacts(),
        ])->layout('components.layouts.dashboard');
    }

    private function getContacts(): LengthAwarePaginator
    {
        return CustomerContact::query()
            ->with('customer')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%")
                        ->orWhere('position', 'like', "%{$this->search}%")
                        ->orWhereHas('customer', function ($q) {
                            $q->where('name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->primary !== '', function ($query) {
                $query->where('is_primary', $this->primary === 'yes');
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
