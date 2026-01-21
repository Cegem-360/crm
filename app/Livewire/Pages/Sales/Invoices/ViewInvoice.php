<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Invoices;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewInvoice extends Component
{
    public Invoice $invoice;

    public function mount(Invoice $invoice): void
    {
        $this->invoice = $invoice->load(['customer', 'order', 'payments']);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.invoices.view-invoice');
    }
}
