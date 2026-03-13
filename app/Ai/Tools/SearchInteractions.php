<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\ChatSession;
use App\Models\Interaction;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

final class SearchInteractions implements Tool
{
    public function description(): Stringable|string
    {
        return 'Search for interactions (interakciók) and chat sessions (beszélgetések) in the CRM by subject, customer name, type, or channel. Returns matching interactions and active chats.';
    }

    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'] ?? null;
        $type = $request['type'] ?? null;
        $includeChatSessions = $request['include_chats'] ?? false;

        $results = [];

        $interactionBuilder = Interaction::query()->with(['customer', 'user']);

        if ($query) {
            $interactionBuilder->where(function ($builder) use ($query): void {
                $builder->where('subject', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhereHas('customer', fn ($customer) => $customer->where('name', 'like', "%{$query}%"));
            });
        }

        if ($type) {
            $interactionBuilder->where('type', $type);
        }

        $interactions = $interactionBuilder->latest('interaction_date')->limit(8)->get();

        if ($interactions->isNotEmpty()) {
            $results[] = "## Interactions\n\n".$interactions->map(function (Interaction $interaction): string {
                $lines = [
                    "**{$interaction->subject}** — {$interaction->type->getLabel()} ({$interaction->status->getLabel()})",
                ];

                if ($interaction->customer) {
                    $lines[] = "Customer: {$interaction->customer->name}";
                }

                $lines[] = "Channel: {$interaction->channel->getLabel()} | Direction: {$interaction->direction->getLabel()}";
                $lines[] = "Date: {$interaction->interaction_date->format('Y-m-d H:i')}";

                if ($interaction->user) {
                    $lines[] = "By: {$interaction->user->name}";
                }

                if ($interaction->description) {
                    $lines[] = 'Description: '.str($interaction->description)->limit(120);
                }

                return implode("\n", $lines);
            })->implode("\n\n---\n\n");
        }

        if ($includeChatSessions) {
            $chatBuilder = ChatSession::query()->with(['customer', 'user']);

            if ($query) {
                $chatBuilder->whereHas('customer', fn ($customer) => $customer->where('name', 'like', "%{$query}%"));
            }

            $chatSessions = $chatBuilder->latest('last_message_at')->limit(5)->get();

            if ($chatSessions->isNotEmpty()) {
                $results[] = "## Chat Sessions\n\n".$chatSessions->map(function (ChatSession $session): string {
                    $lines = [
                        "**Chat #{$session->id}** — {$session->status->getLabel()}",
                    ];

                    if ($session->customer) {
                        $lines[] = "Customer: {$session->customer->name}";
                    }

                    if ($session->user) {
                        $lines[] = "Agent: {$session->user->name}";
                    }

                    if ($session->started_at) {
                        $lines[] = "Started: {$session->started_at->format('Y-m-d H:i')}";
                    }

                    $lines[] = "Unread: {$session->unread_count}";

                    return implode("\n", $lines);
                })->implode("\n\n---\n\n");
            }
        }

        if (empty($results)) {
            return 'No interactions or chat sessions found matching the given criteria.';
        }

        return implode("\n\n", $results);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Search term for subject, description, or customer name'),
            'type' => $schema->string()->enum(['call', 'email', 'meeting', 'note'])->description('Filter by interaction type'),
            'include_chats' => $schema->boolean()->description('If true, also search chat sessions/conversations'),
        ];
    }
}
