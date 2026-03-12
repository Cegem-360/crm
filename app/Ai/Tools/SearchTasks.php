<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

final class SearchTasks implements Tool
{
    public function description(): Stringable|string
    {
        return 'Search for tasks in the CRM by title, description, status, or customer name. Optionally filter by status (pending, in_progress, completed, cancelled) or overdue tasks.';
    }

    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'] ?? null;
        $status = $request['status'] ?? null;
        $overdueOnly = $request['overdue_only'] ?? false;

        $builder = Task::query()->with(['customer', 'assignedUser']);

        if ($query) {
            $builder->where(function ($q) use ($query): void {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$query}%"));
            });
        }

        if ($status) {
            $taskStatus = TaskStatus::tryFrom($status);

            if ($taskStatus) {
                $builder->where('status', $taskStatus);
            }
        }

        if ($overdueOnly) {
            $builder->where('due_date', '<', now())
                ->whereNotIn('status', [TaskStatus::Completed->value, TaskStatus::Cancelled->value]);
        }

        $tasks = $builder->latest('due_date')->limit(10)->get();

        if ($tasks->isEmpty()) {
            return 'No tasks found matching the given criteria.';
        }

        return $tasks->map(function (Task $task): string {
            $lines = [
                "**{$task->title}**",
                "Status: {$task->status->getLabel()} | Priority: {$task->priority->getLabel()}",
            ];

            if ($task->customer) {
                $lines[] = "Customer: {$task->customer->name}";
            }

            if ($task->assignedUser) {
                $lines[] = "Assigned to: {$task->assignedUser->name}";
            }

            if ($task->due_date) {
                $overdue = $task->due_date->isPast() && $task->status !== TaskStatus::Completed;
                $lines[] = 'Due: '.$task->due_date->format('Y-m-d').($overdue ? ' ⚠️ OVERDUE' : '');
            }

            if ($task->description) {
                $lines[] = 'Description: '.str($task->description)->limit(120);
            }

            return implode("\n", $lines);
        })->implode("\n\n---\n\n");
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Search term for task title, description, or customer name'),
            'status' => $schema->string()->enum(['pending', 'in_progress', 'completed', 'cancelled'])->description('Filter by task status'),
            'overdue_only' => $schema->boolean()->description('If true, only return overdue tasks'),
        ];
    }
}
