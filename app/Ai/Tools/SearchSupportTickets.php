<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Complaint;
use App\Models\SupportTicket;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

final class SearchSupportTickets implements Tool
{
    public function description(): Stringable|string
    {
        return 'Search for support tickets (hibajegyek) and complaints (panaszok/bejelentések) in the CRM by ticket number, subject, customer name, status, or priority.';
    }

    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'] ?? null;
        $status = $request['status'] ?? null;
        $includeComplaints = $request['include_complaints'] ?? false;

        $results = [];

        $ticketBuilder = SupportTicket::query()->with(['user', 'assignedUser']);

        if ($query) {
            $ticketBuilder->where(function ($builder) use ($query): void {
                $builder->where('ticket_number', 'like', "%{$query}%")
                    ->orWhere('subject', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if ($status) {
            $ticketBuilder->where('status', $status);
        }

        $tickets = $ticketBuilder->latest()->limit(8)->get();

        if ($tickets->isNotEmpty()) {
            $results[] = "## Support Tickets\n\n".$tickets->map(function (SupportTicket $ticket): string {
                $lines = [
                    "**{$ticket->ticket_number}** — {$ticket->subject}",
                    "Status: {$ticket->status->getLabel()} | Priority: {$ticket->priority->getLabel()} | Category: {$ticket->category->getLabel()}",
                ];

                if ($ticket->assignedUser) {
                    $lines[] = "Assigned to: {$ticket->assignedUser->name}";
                }

                if ($ticket->resolved_at) {
                    $lines[] = "Resolved: {$ticket->resolved_at->format('Y-m-d H:i')}";
                }

                if ($ticket->description) {
                    $lines[] = 'Description: '.str($ticket->description)->limit(120);
                }

                return implode("\n", $lines);
            })->implode("\n\n---\n\n");
        }

        if ($includeComplaints) {
            $complaintBuilder = Complaint::query()->with(['customer', 'assignedUser']);

            if ($query) {
                $complaintBuilder->where(function ($builder) use ($query): void {
                    $builder->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhereHas('customer', fn ($customer) => $customer->where('name', 'like', "%{$query}%"));
                });
            }

            if ($status) {
                $complaintBuilder->where('status', $status);
            }

            $complaints = $complaintBuilder->latest('reported_at')->limit(5)->get();

            if ($complaints->isNotEmpty()) {
                $results[] = "## Complaints\n\n".$complaints->map(function (Complaint $complaint): string {
                    $lines = [
                        "**{$complaint->title}** — {$complaint->status->getLabel()}",
                        "Severity: {$complaint->severity->getLabel()}",
                    ];

                    if ($complaint->customer) {
                        $lines[] = "Customer: {$complaint->customer->name}";
                    }

                    if ($complaint->assignedUser) {
                        $lines[] = "Assigned to: {$complaint->assignedUser->name}";
                    }

                    if ($complaint->reported_at) {
                        $lines[] = "Reported: {$complaint->reported_at->format('Y-m-d')}";
                    }

                    if ($complaint->description) {
                        $lines[] = 'Description: '.str($complaint->description)->limit(120);
                    }

                    return implode("\n", $lines);
                })->implode("\n\n---\n\n");
            }
        }

        if (empty($results)) {
            return 'No support tickets or complaints found matching the given criteria.';
        }

        return implode("\n\n", $results);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Search term for ticket number, subject, description, or customer name'),
            'status' => $schema->string()->enum(['open', 'in_progress', 'waiting_on_customer', 'resolved', 'closed'])->description('Filter by status'),
            'include_complaints' => $schema->boolean()->description('If true, also search complaints/bug reports'),
        ];
    }
}
