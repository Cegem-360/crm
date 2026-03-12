<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Opportunity;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

final class SearchOpportunities implements Tool
{
    public function description(): Stringable|string
    {
        return 'Search for sales opportunities in the CRM by title, description, or customer name. Optionally filter by stage (lead, qualified, proposal, negotiation, sent_quotation, lost_quotation).';
    }

    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'] ?? null;
        $stage = $request['stage'] ?? null;
        $activeOnly = $request['active_only'] ?? false;

        $builder = Opportunity::query()->with(['customer', 'assignedUser']);

        if ($query) {
            $builder->where(function ($q) use ($query): void {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$query}%"));
            });
        }

        if ($stage) {
            $builder->where('stage', $stage);
        }

        if ($activeOnly) {
            $builder->whereIn('stage', ['lead', 'qualified', 'proposal', 'negotiation']);
        }

        $opportunities = $builder->latest('expected_close_date')->limit(10)->get();

        if ($opportunities->isEmpty()) {
            return 'No opportunities found matching the given criteria.';
        }

        return $opportunities->map(function (Opportunity $opportunity): string {
            $lines = [
                "**{$opportunity->title}**",
                "Stage: {$opportunity->stage->getLabel()}",
            ];

            if ($opportunity->customer) {
                $lines[] = "Customer: {$opportunity->customer->name}";
            }

            if ($opportunity->value) {
                $lines[] = 'Value: '.number_format((float) $opportunity->value, 2).' (Probability: '.$opportunity->probability.'%)';
            }

            if ($opportunity->assignedUser) {
                $lines[] = "Assigned to: {$opportunity->assignedUser->name}";
            }

            if ($opportunity->expected_close_date) {
                $lines[] = 'Expected close: '.$opportunity->expected_close_date->format('Y-m-d');
            }

            if ($opportunity->description) {
                $lines[] = 'Description: '.str($opportunity->description)->limit(120);
            }

            return implode("\n", $lines);
        })->implode("\n\n---\n\n");
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Search term for opportunity title, description, or customer name'),
            'stage' => $schema->string()->enum(['lead', 'qualified', 'proposal', 'negotiation', 'sent_quotation', 'lost_quotation'])->description('Filter by opportunity stage'),
            'active_only' => $schema->boolean()->description('If true, only return active opportunities (lead, qualified, proposal, negotiation)'),
        ];
    }
}
