<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Quotes;

use App\Enums\QuoteStatus;
use App\Filament\Exports\QuoteExporter;
use App\Filament\Imports\QuoteImporter;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Quote;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ImportAction;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ListQuotes extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
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

    public function importAction(): ImportAction
    {
        return ImportAction::make('import')
            ->importer(QuoteImporter::class);
    }

    public function exportAction(): ExportAction
    {
        return ExportAction::make('export')
            ->exporter(QuoteExporter::class)
            ->formats([
                ExportFormat::Xlsx,
                ExportFormat::Csv,
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.quotes.list-quotes', [
            'quotes' => $this->getQuotes(),
            'statuses' => QuoteStatus::cases(),
        ]);
    }

    private function getQuotes(): LengthAwarePaginator
    {
        return Quote::query()
            ->with(['customer', 'opportunity'])
            ->withCount('items')
            ->when($this->search !== '', function ($query): void {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search): void {
                    $q->where('quote_number', 'like', $search)
                        ->orWhereHas('customer', function ($customerQuery) use ($search): void {
                            $customerQuery->where('name', 'like', $search);
                        });
                });
            })
            ->when($this->status !== '', function ($query): void {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
