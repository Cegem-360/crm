<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Team;
use App\Services\LeadScoringService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

final class CalculateLeadScores implements ShouldQueue
{
    use Queueable;

    public function handle(LeadScoringService $service): void
    {
        $teams = Team::all();

        foreach ($teams as $team) {
            $updated = $service->calculateForTeam($team);
            $assigned = $service->assignLeadsRoundRobin($team);

            Log::info("Lead scores calculated for team {$team->name}: {$updated} customers scored, {$assigned} leads assigned.");
        }
    }
}
