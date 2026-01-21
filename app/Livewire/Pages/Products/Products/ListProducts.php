<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products\Products;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ListProducts extends Component
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
    public string $category = '';

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

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedActive(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.pages.products.products.list-products', [
            'products' => $this->getProducts(),
            'categories' => $this->getCategories(),
        ]);
    }

    private function getProducts(): LengthAwarePaginator
    {
        return Product::query()
            ->with(['category'])
            ->when($this->search !== '', function ($query) {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                        ->orWhere('sku', 'like', $search);
                });
            })
            ->when($this->category !== '', function ($query) {
                $query->where('category_id', $this->category);
            })
            ->when($this->active !== '', function ($query) {
                $query->where('is_active', $this->active === '1');
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }

    /**
     * @return Collection<int, ProductCategory>
     */
    private function getCategories(): Collection
    {
        return ProductCategory::query()->orderBy('name')->get();
    }
}
