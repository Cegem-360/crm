<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Invoices;

use App\Filament\Resources\Invoices\Schemas\InvoiceForm;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Invoice;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditInvoice extends Component implements HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithSchemas;

    public ?Invoice $invoice = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?Invoice $invoice = null): void
    {
        $this->invoice = $invoice;

        $this->form->fill($invoice?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return InvoiceForm::configure($schema)
            ->statePath('data')
            ->model($this->invoice ?? Invoice::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->invoice?->exists) {
            $this->invoice->update($data);
            $message = __('Invoice updated successfully.');
        } else {
            $this->invoice = Invoice::query()->create(array_merge($data, ['team_id' => $this->team->id]));
            $this->form->model($this->invoice)->saveRelationships();
            $message = __('Invoice created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.invoices.view', ['team' => $this->team, 'invoice' => $this->invoice]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.invoices.edit-invoice');
    }
}
