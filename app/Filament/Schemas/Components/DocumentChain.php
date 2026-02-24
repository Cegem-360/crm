<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Components;

use App\Models\Invoice;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Quote;
use App\Models\Team;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Throwable;

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
        $team = $tenant ?? (app()->bound('current_team') ? resolve('current_team') : null);

        return [
            [
                'type' => 'opportunity',
                'label' => __('Opportunity'),
                'number' => $opportunity?->title,
                'url' => $opportunity ? $this->generateUrl('opportunity', $opportunity, $tenant, $team) : null,
                'current' => $currentType === 'opportunity',
            ],
            [
                'type' => 'quote',
                'label' => __('Quote'),
                'number' => $quote?->quote_number,
                'url' => $quote ? $this->generateUrl('quote', $quote, $tenant, $team) : null,
                'current' => $currentType === 'quote',
            ],
            [
                'type' => 'order',
                'label' => __('Order'),
                'number' => $order?->order_number,
                'url' => $order ? $this->generateUrl('order', $order, $tenant, $team) : null,
                'current' => $currentType === 'order',
            ],
            [
                'type' => 'invoice',
                'label' => __('Invoice'),
                'number' => $invoice?->invoice_number,
                'url' => $invoice ? $this->generateUrl('invoice', $invoice, $tenant, $team) : null,
                'current' => $currentType === 'invoice',
            ],
        ];
    }

    private function generateUrl(string $type, Model $record, ?Team $tenant, ?Team $team): ?string
    {
        $dashboardRouteMap = [
            'opportunity' => ['name' => 'dashboard.opportunities.edit', 'param' => 'opportunity'],
            'quote' => ['name' => 'dashboard.quotes.edit', 'param' => 'quote'],
            'order' => ['name' => 'dashboard.orders.edit', 'param' => 'order'],
            'invoice' => ['name' => 'dashboard.invoices.view', 'param' => 'invoice'],
        ];

        $filamentRouteMap = [
            'opportunity' => 'filament.admin.resources.lead-opportunities.edit',
            'quote' => 'filament.admin.resources.quotes.edit',
            'order' => 'filament.admin.resources.orders.edit',
            'invoice' => 'filament.admin.resources.invoices.view',
        ];

        $resolvedTeam = $team ?? $tenant;

        if (! $resolvedTeam instanceof Team) {
            return null;
        }

        // Prefer frontend dashboard routes when outside the Filament admin panel
        if (! $tenant || app()->bound('current_team')) {
            try {
                $dashboard = $dashboardRouteMap[$type];

                return route($dashboard['name'], ['team' => $resolvedTeam, $dashboard['param'] => $record]);
            } catch (Throwable) {
                // Fall through to Filament routes
            }
        }

        if ($tenant instanceof Team) {
            try {
                return route($filamentRouteMap[$type], ['record' => $record, 'tenant' => $tenant->slug]);
            } catch (Throwable) {
                return null;
            }
        }

        return null;
    }
}
