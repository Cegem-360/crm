<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Quote;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

final class SearchQuotes implements Tool
{
    public function description(): Stringable|string
    {
        return 'Search for quotes (árajánlatok) in the CRM by quote number, customer name, or status. Returns matching quotes with their details, amounts, and validity.';
    }

    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'] ?? null;
        $status = $request['status'] ?? null;

        $builder = Quote::query()->with(['customer', 'items']);

        if ($query) {
            $builder->where(function ($builder) use ($query): void {
                $builder->where('quote_number', 'like', "%{$query}%")
                    ->orWhere('notes', 'like', "%{$query}%")
                    ->orWhereHas('customer', fn ($customer) => $customer->where('name', 'like', "%{$query}%"));
            });
        }

        if ($status) {
            $builder->where('status', $status);
        }

        $quotes = $builder->latest('issue_date')->limit(8)->get();

        if ($quotes->isEmpty()) {
            return 'No quotes found matching the given criteria.';
        }

        return $quotes->map(function (Quote $quote): string {
            $lines = [
                "**{$quote->quote_number}** — {$quote->status->getLabel()}",
            ];

            if ($quote->customer) {
                $lines[] = "Customer: {$quote->customer->name}";
            }

            $lines[] = 'Total: '.number_format((float) $quote->total, 2);
            $lines[] = "Issued: {$quote->issue_date->format('Y-m-d')}";

            if ($quote->valid_until) {
                $expired = $quote->valid_until->isPast();
                $lines[] = 'Valid until: '.$quote->valid_until->format('Y-m-d').($expired ? ' ⚠️ EXPIRED' : '');
            }

            $lines[] = "Items: {$quote->items->count()}";

            if ($quote->notes) {
                $lines[] = 'Notes: '.str($quote->notes)->limit(120);
            }

            return implode("\n", $lines);
        })->implode("\n\n---\n\n");
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Search term for quote number, customer name, or notes'),
            'status' => $schema->string()->enum(['draft', 'sent', 'accepted', 'rejected', 'expired'])->description('Filter by quote status'),
        ];
    }
}
