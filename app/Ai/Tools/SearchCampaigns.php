<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Campaign;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

final class SearchCampaigns implements Tool
{
    public function description(): Stringable|string
    {
        return 'Search for marketing campaigns (kampányok) in the CRM by name, description, or status. Returns matching campaigns with their dates, status, and response counts.';
    }

    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'] ?? null;
        $status = $request['status'] ?? null;

        $builder = Campaign::query()->withCount('responses');

        if ($query) {
            $builder->where(function ($builder) use ($query): void {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if ($status) {
            $builder->where('status', $status);
        }

        $campaigns = $builder->latest('start_date')->limit(8)->get();

        if ($campaigns->isEmpty()) {
            return 'No campaigns found matching the given criteria.';
        }

        return $campaigns->map(function (Campaign $campaign): string {
            $lines = [
                "**{$campaign->name}** — {$campaign->status->getLabel()}",
            ];

            if ($campaign->start_date) {
                $lines[] = "Start: {$campaign->start_date->format('Y-m-d')}";
            }

            if ($campaign->end_date) {
                $lines[] = "End: {$campaign->end_date->format('Y-m-d')}";
            }

            $lines[] = "Responses: {$campaign->responses_count}";

            if ($campaign->description) {
                $lines[] = 'Description: '.str($campaign->description)->limit(150);
            }

            return implode("\n", $lines);
        })->implode("\n\n---\n\n");
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Search term for campaign name or description'),
            'status' => $schema->string()->enum(['draft', 'active', 'paused', 'completed', 'cancelled'])->description('Filter by campaign status'),
        ];
    }
}
