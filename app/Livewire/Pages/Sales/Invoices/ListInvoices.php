<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Invoices;

use App\Enums\InvoiceStatus;
use App\Filament\Exports\InvoiceExporter;
use App\Filament\Imports\InvoiceImporter;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Invoice;
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
final class ListInvoices extends Component implements HasActions, HasSchemas
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
            ->importer(InvoiceImporter::class);
    }

    public function exportAction(): ExportAction
    {
        return ExportAction::make('export')
            ->exporter(InvoiceExporter::class)
            ->formats([
                ExportFormat::Xlsx,
                ExportFormat::Csv,
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.invoices.list-invoices', [
            'invoices' => $this->getInvoices(),
            'statuses' => InvoiceStatus::cases(),
        ]);
    }

    private function getInvoices(): LengthAwarePaginator
    {
        return Invoice::query()
            ->with(['customer', 'order'])
            ->when($this->search !== '', function ($query): void {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search): void {
                    $q->where('invoice_number', 'like', $search)
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
