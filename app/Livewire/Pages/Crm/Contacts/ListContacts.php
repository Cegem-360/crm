<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Crm\Contacts;

use App\Filament\Exports\CustomerContactExporter;
use App\Filament\Imports\CustomerContactImporter;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\CustomerContact;
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
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ListContacts extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
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

    #[On('contact-saved')]
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

    public function updatedPrimary(): void
    {
        $this->resetPage();
    }

    public function importAction(): ImportAction
    {
        return ImportAction::make('import')
            ->importer(CustomerContactImporter::class);
    }

    public function exportAction(): ExportAction
    {
        return ExportAction::make('export')
            ->exporter(CustomerContactExporter::class)
            ->formats([
                ExportFormat::Xlsx,
                ExportFormat::Csv,
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.crm.contacts.list-contacts', [
            'contacts' => $this->getContacts(),
        ]);
    }

    private function getContacts(): LengthAwarePaginator
    {
        return CustomerContact::query()
            ->with('customer')
            ->when($this->search, function ($query): void {
                $query->where(function ($q): void {
                    $q->where('name', 'like', sprintf('%%%s%%', $this->search))
                        ->orWhere('email', 'like', sprintf('%%%s%%', $this->search))
                        ->orWhere('phone', 'like', sprintf('%%%s%%', $this->search))
                        ->orWhere('position', 'like', sprintf('%%%s%%', $this->search))
                        ->orWhereHas('customer', function ($q): void {
                            $q->where('name', 'like', sprintf('%%%s%%', $this->search));
                        });
                });
            })
            ->when($this->primary !== '', function ($query): void {
                $query->where('is_primary', $this->primary === 'yes');
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
