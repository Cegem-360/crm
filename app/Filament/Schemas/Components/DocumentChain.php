<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Components;

use App\Models\Invoice;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Quote;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Model;

final class DocumentChain extends Component
{
    protected string $view = 'filament.schemas.components.document-chain';

    public static function make(): static
    {
        return resolve(self::class);
    }

    /**
     * @return array<int, array{type: string, label: string, number: string|null, url: string|null, current: bool}>
     */
    public function getChainItems(): array
    {
        $record = $this->getRecord();

        if (! $record) {
            return [];
        }

        return $this->buildChain($record);
    }

    /**
     * @return array<int, array{type: string, label: string, number: string|null, url: string|null, current: bool}>
     */
    private function buildChain(Model $record): array
    {
        $opportunity = null;
        $quote = null;
        $order = null;
        $invoice = null;
        $currentType = null;

        if ($record instanceof Opportunity) {
            $currentType = 'opportunity';
            $opportunity = $record;
            $quote = $record->quotes()->first();
            $order = $quote?->orders()->first();
            $invoice = $order?->invoices()->first();
        } elseif ($record instanceof Quote) {
            $currentType = 'quote';
            $quote = $record;
            $opportunity = $record->opportunity;
            $order = $record->orders()->first();
            $invoice = $order?->invoices()->first();
        } elseif ($record instanceof Order) {
            $currentType = 'order';
            $order = $record;
            $quote = $record->quote;
            $opportunity = $quote?->opportunity;
            $invoice = $record->invoices()->first();
        } elseif ($record instanceof Invoice) {
            $currentType = 'invoice';
            $invoice = $record;
            $order = $record->order;
            $quote = $order?->quote;
            $opportunity = $quote?->opportunity;
        }

        $tenant = Filament::getTenant();

        return [
            [
                'type' => 'opportunity',
                'label' => __('Opportunity'),
                'number' => $opportunity?->title,
                'url' => $opportunity ? route('filament.admin.resources.lead-opportunities.edit', ['record' => $opportunity, 'tenant' => $tenant]) : null,
                'current' => $currentType === 'opportunity',
            ],
            [
                'type' => 'quote',
                'label' => __('Quote'),
                'number' => $quote?->quote_number,
                'url' => $quote ? route('filament.admin.resources.quotes.edit', ['record' => $quote, 'tenant' => $tenant]) : null,
                'current' => $currentType === 'quote',
            ],
            [
                'type' => 'order',
                'label' => __('Order'),
                'number' => $order?->order_number,
                'url' => $order ? route('filament.admin.resources.orders.edit', ['record' => $order, 'tenant' => $tenant]) : null,
                'current' => $currentType === 'order',
            ],
            [
                'type' => 'invoice',
                'label' => __('Invoice'),
                'number' => $invoice?->invoice_number,
                'url' => $invoice ? route('filament.admin.resources.invoices.view', ['record' => $invoice, 'tenant' => $tenant]) : null,
                'current' => $currentType === 'invoice',
            ],
        ];
    }
}
