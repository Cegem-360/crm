<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Invoices;

use App\Filament\Resources\Invoices\Schemas\InvoiceInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Invoice;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewInvoice extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Invoice $invoice;

    public function mount(Invoice $invoice): void
    {
        $this->invoice = $invoice;
    }

    public function infolist(Schema $schema): Schema
    {
        return InvoiceInfolist::configure($schema)
            ->record($this->invoice)
            ->columns(2);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.invoices.view-invoice');
    }
}
