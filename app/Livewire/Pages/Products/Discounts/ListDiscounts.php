<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products\Discounts;

use App\Enums\DiscountType;
use App\Models\Discount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ListDiscounts extends Component
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
    public string $type = '';

    #[Url]
    public string $active = '';

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

    public function updatedActive(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.pages.products.discounts.list-discounts', [
            'discounts' => $this->getDiscounts(),
            'types' => DiscountType::cases(),
        ]);
    }

    private function getDiscounts(): LengthAwarePaginator
    {
        return Discount::query()
            ->with(['customer', 'product'])
            ->when($this->search !== '', function ($query) {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', $search);
                        })
                        ->orWhereHas('product', function ($productQuery) use ($search) {
                            $productQuery->where('name', 'like', $search);
                        });
                });
            })
            ->when($this->type !== '', function ($query) {
                $query->where('type', $this->type);
            })
            ->when($this->active !== '', function ($query) {
                $query->where('is_active', $this->active === '1');
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
