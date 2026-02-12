<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Interactions\Interactions;

use App\Enums\InteractionStatus;
use App\Enums\InteractionType;
use App\Filament\Exports\InteractionExporter;
use App\Filament\Imports\InteractionImporter;
use App\Models\Interaction;
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
final class ListInteractions extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $sortBy = 'interaction_date';

    #[Url]
    public string $sortDir = 'desc';

    #[Url]
    public int $perPage = 10;

    #[Url]
    public string $type = '';

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

    public function updatedType(): void
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
            ->importer(InteractionImporter::class);
    }

    public function exportAction(): ExportAction
    {
        return ExportAction::make('export')
            ->exporter(InteractionExporter::class)
            ->formats([
                ExportFormat::Xlsx,
                ExportFormat::Csv,
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.interactions.interactions.list-interactions', [
            'interactions' => $this->getInteractions(),
            'types' => InteractionType::cases(),
            'statuses' => InteractionStatus::cases(),
        ]);
    }

    private function getInteractions(): LengthAwarePaginator
    {
        return Interaction::query()
            ->with(['customer', 'contact', 'user'])
            ->when($this->search !== '', function ($query): void {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search): void {
                    $q->where('subject', 'like', $search)
                        ->orWhereHas('customer', function ($customerQuery) use ($search): void {
                            $customerQuery->where('name', 'like', $search);
                        });
                });
            })
            ->when($this->type !== '', function ($query): void {
                $query->where('type', $this->type);
            })
            ->when($this->status !== '', function ($query): void {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
