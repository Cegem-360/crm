<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Customer;
use App\Models\LeadScore;
use App\Models\Team;
use App\Models\User;
use App\Notifications\LeadAssignedNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;

final class LeadScoringService
{
    private const int MAX_INTERACTION_SCORE = 30;

    private const int MAX_RECENCY_SCORE = 25;

    private const int MAX_OPPORTUNITY_SCORE = 30;

    private const int MAX_ENGAGEMENT_SCORE = 15;

    public function calculateForTeam(Team $team): int
    {
        $customers = Customer::query()
            ->where('team_id', $team->id)
            ->where('is_active', true)
            ->with(['interactions', 'opportunities', 'quotes', 'orders'])
            ->get();

        $updated = 0;

        foreach ($customers as $customer) {
            $this->calculateForCustomer($customer, $team);
            $updated++;
        }

        return $updated;
    }

    public function calculateForCustomer(Customer $customer, Team $team): LeadScore
    {
        $interactionScore = $this->calculateInteractionScore($customer);
        $recencyScore = $this->calculateRecencyScore($customer);
        $opportunityScore = $this->calculateOpportunityScore($customer);
        $engagementScore = $this->calculateEngagementScore($customer);

        $totalScore = $interactionScore + $recencyScore + $opportunityScore + $engagementScore;

        return LeadScore::query()->updateOrCreate(
            [
                'team_id' => $team->id,
                'customer_id' => $customer->id,
            ],
            [
                'score' => $totalScore,
                'interaction_score' => $interactionScore,
                'recency_score' => $recencyScore,
                'opportunity_score' => $opportunityScore,
                'engagement_score' => $engagementScore,
                'last_calculated_at' => now(),
            ],
        );
    }

    public function assignLeadsRoundRobin(Team $team, int $minimumScore = 50): int
    {
        $unassignedLeads = LeadScore::query()
            ->where('team_id', $team->id)
            ->where('score', '>=', $minimumScore)
            ->whereNull('assigned_to')
            ->orderByDesc('score')
            ->get();

        if ($unassignedLeads->isEmpty()) {
            return 0;
        }

        $salesUsers = $team->users()->get();

        if ($salesUsers->isEmpty()) {
            return 0;
        }

        $assignmentCounts = LeadScore::query()
            ->where('team_id', $team->id)
            ->whereNotNull('assigned_to')
            ->selectRaw('assigned_to, count(*) as total')
            ->groupBy('assigned_to')
            ->pluck('total', 'assigned_to');

        $assigned = 0;

        foreach ($unassignedLeads as $leadScore) {
            $nextUser = $this->getNextUserForAssignment($salesUsers, $assignmentCounts);

            if (! $nextUser instanceof User) {
                break;
            }

            $leadScore->update([
                'assigned_to' => $nextUser->id,
                'assigned_at' => now(),
            ]);

            $assignmentCounts[$nextUser->id] = ($assignmentCounts[$nextUser->id] ?? 0) + 1;

            $nextUser->notify(new LeadAssignedNotification($leadScore));

            $assigned++;
        }

        return $assigned;
    }

    private function calculateInteractionScore(Customer $customer): int
    {
        $interactionCount = $customer->interactions->count();

        if ($interactionCount === 0) {
            return 0;
        }

        // Up to 30 points: 3 points per interaction, max 10 interactions
        return min($interactionCount * 3, self::MAX_INTERACTION_SCORE);
    }

    private function calculateRecencyScore(Customer $customer): int
    {
        $lastInteraction = $customer->interactions->max('interaction_date');

        if ($lastInteraction === null) {
            return 0;
        }

        $daysSinceLastInteraction = Date::parse($lastInteraction)->diffInDays(now());

        // More recent = higher score. 25 points if today, decreasing by 1 per day
        return max(0, min(self::MAX_RECENCY_SCORE, self::MAX_RECENCY_SCORE - (int) $daysSinceLastInteraction));
    }

    private function calculateOpportunityScore(Customer $customer): int
    {
        $opportunityCount = $customer->opportunities->count();
        $totalValue = $customer->opportunities->sum('value');

        if ($opportunityCount === 0) {
            return 0;
        }

        // 5 points per opportunity (max 15)
        $countScore = min($opportunityCount * 5, 15);

        // Up to 15 points based on total value (1 point per 100,000 HUF)
        $valueScore = min((int) ($totalValue / 100_000), 15);

        return min($countScore + $valueScore, self::MAX_OPPORTUNITY_SCORE);
    }

    private function calculateEngagementScore(Customer $customer): int
    {
        $score = 0;

        // 5 points if has quotes
        if ($customer->quotes->isNotEmpty()) {
            $score += 5;
        }

        // 5 points if has orders
        if ($customer->orders->isNotEmpty()) {
            $score += 5;
        }

        // 5 points if has email/phone set (complete profile)
        if (filled($customer->email) && filled($customer->phone)) {
            $score += 5;
        }

        return min($score, self::MAX_ENGAGEMENT_SCORE);
    }

    /**
     * @param  Collection<int, User>  $users
     * @param  \Illuminate\Support\Collection<int, int>  $assignmentCounts
     */
    private function getNextUserForAssignment($users, $assignmentCounts): ?User
    {
        $minCount = PHP_INT_MAX;
        $nextUser = null;

        foreach ($users as $user) {
            $count = $assignmentCounts[$user->id] ?? 0;

            if ($count < $minCount) {
                $minCount = $count;
                $nextUser = $user;
            }
        }

        return $nextUser;
    }
}
