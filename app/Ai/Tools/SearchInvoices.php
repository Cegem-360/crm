<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Invoice;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

final class SearchInvoices implements Tool
{
    public function description(): Stringable|string
    {
        return 'Search for invoices (számlák) in the CRM by invoice number, customer name, or status. Returns matching invoices with amounts, due dates, and payment status.';
    }

    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'] ?? null;
        $status = $request['status'] ?? null;
        $overdueOnly = $request['overdue_only'] ?? false;

        $builder = Invoice::query()->with(['customer']);

        if ($query) {
            $builder->where(function ($builder) use ($query): void {
                $builder->where('invoice_number', 'like', "%{$query}%")
                    ->orWhere('notes', 'like', "%{$query}%")
                    ->orWhereHas('customer', fn ($customer) => $customer->where('name', 'like', "%{$query}%"));
            });
        }

        if ($status) {
            $builder->where('status', $status);
        }

        if ($overdueOnly) {
            $builder->where('due_date', '<', now())
                ->whereNotIn('status', ['paid', 'cancelled']);
        }

        $invoices = $builder->latest('issue_date')->limit(8)->get();

        if ($invoices->isEmpty()) {
            return 'No invoices found matching the given criteria.';
        }

        return $invoices->map(function (Invoice $invoice): string {
            $lines = [
                "**{$invoice->invoice_number}** — {$invoice->status->getLabel()}",
            ];

            if ($invoice->customer) {
                $lines[] = "Customer: {$invoice->customer->name}";
            }

            $lines[] = 'Total: '.number_format((float) $invoice->total, 2);
            $lines[] = "Issued: {$invoice->issue_date->format('Y-m-d')}";

            if ($invoice->due_date) {
                $overdue = $invoice->due_date->isPast() && ! in_array($invoice->status->value, ['paid', 'cancelled'], true);
                $lines[] = 'Due: '.$invoice->due_date->format('Y-m-d').($overdue ? ' ⚠️ OVERDUE' : '');
            }

            if ($invoice->paid_at) {
                $lines[] = "Paid at: {$invoice->paid_at->format('Y-m-d')}";
            }

            if ($invoice->notes) {
                $lines[] = 'Notes: '.str($invoice->notes)->limit(120);
            }

            return implode("\n", $lines);
        })->implode("\n\n---\n\n");
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Search term for invoice number, customer name, or notes'),
            'status' => $schema->string()->enum(['draft', 'active', 'completed', 'cancelled', 'paid'])->description('Filter by invoice status'),
            'overdue_only' => $schema->boolean()->description('If true, only return overdue unpaid invoices'),
        ];
    }
}
