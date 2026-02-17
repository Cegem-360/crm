<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Sales\Quotes;

use App\Filament\Resources\Quotes\Schemas\QuoteForm;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Quote;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditQuote extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?Quote $quote = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?Quote $quote = null): void
    {
        $this->quote = $quote;

        $this->form->fill($quote?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return QuoteForm::configure($schema)
            ->statePath('data')
            ->model($this->quote ?? Quote::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $data = $this->calculateTotalsFromItems($data);

        if ($this->quote?->exists) {
            $this->quote->update($data);
            $message = __('Quote updated successfully.');
        } else {
            $this->quote = Quote::query()->create(array_merge($data, ['team_id' => $this->team->id]));
            $this->form->model($this->quote)->saveRelationships();
            $message = __('Quote created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.quotes.view', ['team' => $this->team, 'quote' => $this->quote]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.sales.quotes.edit-quote');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function calculateTotalsFromItems(array $data): array
    {
        $subtotal = 0.0;
        $totalDiscount = 0.0;
        $totalTax = 0.0;

        $items = $this->data['items'] ?? [];

        foreach ($items as $item) {
            $quantity = (float) ($item['quantity'] ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $discountPercent = (float) ($item['discount_percent'] ?? 0);
            $taxRate = (float) ($item['tax_rate'] ?? 0);

            $lineTotal = $quantity * $unitPrice;
            $discountAmount = $lineTotal * ($discountPercent / 100);
            $taxableAmount = $lineTotal - $discountAmount;

            $subtotal += $lineTotal;
            $totalDiscount += $discountAmount;
            $totalTax += $taxableAmount * ($taxRate / 100);
        }

        $data['subtotal'] = $subtotal;
        $data['discount_amount'] = $totalDiscount;
        $data['tax_amount'] = $totalTax;
        $data['total'] = $subtotal - $totalDiscount + $totalTax;

        return $data;
    }
}
