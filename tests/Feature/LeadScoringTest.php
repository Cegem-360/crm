<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Interaction;
use App\Models\LeadScore;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Quote;
use App\Models\Team;
use App\Models\User;
use App\Notifications\LeadAssignedNotification;
use App\Services\LeadScoringService;
use Illuminate\Support\Facades\Notification;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->service = new LeadScoringService;
});

it('calculates zero score for customer with no activity', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'is_active' => true,
        'email' => null,
        'phone' => null,
    ]);

    $leadScore = $this->service->calculateForCustomer($customer, $this->team);

    expect($leadScore->score)->toBe(0)
        ->and($leadScore->interaction_score)->toBe(0)
        ->and($leadScore->recency_score)->toBe(0)
        ->and($leadScore->opportunity_score)->toBe(0)
        ->and($leadScore->engagement_score)->toBe(0);
});

it('calculates interaction score based on interaction count', function (): void {
    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);

    Interaction::factory()->count(5)->for($customer)->for($this->team)->create([
        'interaction_date' => now()->subDays(30),
    ]);

    $customer->load('interactions');
    $leadScore = $this->service->calculateForCustomer($customer, $this->team);

    // 5 interactions × 3 points = 15
    expect($leadScore->interaction_score)->toBe(15);
});

it('caps interaction score at maximum', function (): void {
    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);

    Interaction::factory()->count(15)->for($customer)->for($this->team)->create([
        'interaction_date' => now()->subDays(30),
    ]);

    $customer->load('interactions');
    $leadScore = $this->service->calculateForCustomer($customer, $this->team);

    // 15 × 3 = 45 but capped at 30
    expect($leadScore->interaction_score)->toBe(30);
});

it('calculates recency score based on last interaction date', function (): void {
    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);

    Interaction::factory()->for($customer)->for($this->team)->create([
        'interaction_date' => now(),
    ]);

    $customer->load('interactions');
    $leadScore = $this->service->calculateForCustomer($customer, $this->team);

    // Interaction today = 25 points (max recency)
    expect($leadScore->recency_score)->toBe(25);
});

it('calculates lower recency score for older interactions', function (): void {
    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);

    Interaction::factory()->for($customer)->for($this->team)->create([
        'interaction_date' => now()->subDays(10),
    ]);

    $customer->load('interactions');
    $leadScore = $this->service->calculateForCustomer($customer, $this->team);

    // 25 - 10 days = 15
    expect($leadScore->recency_score)->toBe(15);
});

it('calculates opportunity score from count and value', function (): void {
    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);

    Opportunity::factory()->count(2)->for($customer)->for($this->team)->create([
        'value' => 200_000,
    ]);

    $customer->load('opportunities');
    $leadScore = $this->service->calculateForCustomer($customer, $this->team);

    // 2 opportunities × 5 = 10 (count) + 400,000 / 100,000 = 4 (value) = 14
    expect($leadScore->opportunity_score)->toBe(14);
});

it('calculates engagement score from quotes, orders, and profile', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'is_active' => true,
        'email' => 'test@example.com',
        'phone' => '+36301234567',
    ]);

    Quote::factory()->for($customer)->for($this->team)->create();
    Order::factory()->for($customer)->for($this->team)->create();

    $customer->load(['quotes', 'orders']);
    $leadScore = $this->service->calculateForCustomer($customer, $this->team);

    // quotes: 5 + orders: 5 + complete profile: 5 = 15
    expect($leadScore->engagement_score)->toBe(15);
});

it('calculates scores for all active customers in a team', function (): void {
    $activeCustomer = Customer::factory()->for($this->team)->create(['is_active' => true]);
    $inactiveCustomer = Customer::factory()->for($this->team)->create(['is_active' => false]);

    $updated = $this->service->calculateForTeam($this->team);

    expect($updated)->toBe(1)
        ->and(LeadScore::query()->where('customer_id', $activeCustomer->id)->exists())->toBeTrue()
        ->and(LeadScore::query()->where('customer_id', $inactiveCustomer->id)->exists())->toBeFalse();
});

it('updates existing lead score on recalculation', function (): void {
    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);

    $this->service->calculateForCustomer($customer, $this->team);

    Interaction::factory()->count(3)->for($customer)->for($this->team)->create([
        'interaction_date' => now(),
    ]);

    $customer->load('interactions');
    $leadScore = $this->service->calculateForCustomer($customer, $this->team);

    expect(LeadScore::query()->where('customer_id', $customer->id)->count())->toBe(1)
        ->and($leadScore->interaction_score)->toBe(9);
});

it('assigns leads round-robin to team members', function (): void {
    Notification::fake();

    $userOne = User::factory()->create();
    $userTwo = User::factory()->create();
    $this->team->users()->attach([$userOne->id, $userTwo->id]);

    $customerOne = Customer::factory()->for($this->team)->create(['is_active' => true]);
    $customerTwo = Customer::factory()->for($this->team)->create(['is_active' => true]);

    LeadScore::factory()->forTeam($this->team)->create([
        'customer_id' => $customerOne->id,
        'score' => 80,
    ]);
    LeadScore::factory()->forTeam($this->team)->create([
        'customer_id' => $customerTwo->id,
        'score' => 60,
    ]);

    $assigned = $this->service->assignLeadsRoundRobin($this->team);

    expect($assigned)->toBe(2);

    $assignments = LeadScore::query()
        ->where('team_id', $this->team->id)
        ->whereNotNull('assigned_to')
        ->pluck('assigned_to');

    expect($assignments)->toContain($userOne->id)
        ->and($assignments)->toContain($userTwo->id);

    Notification::assertSentTo($userOne, LeadAssignedNotification::class);
    Notification::assertSentTo($userTwo, LeadAssignedNotification::class);
});

it('does not assign leads below minimum score', function (): void {
    Notification::fake();

    $user = User::factory()->create();
    $this->team->users()->attach($user->id);

    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);

    LeadScore::factory()->forTeam($this->team)->create([
        'customer_id' => $customer->id,
        'score' => 30,
    ]);

    $assigned = $this->service->assignLeadsRoundRobin($this->team);

    expect($assigned)->toBe(0);

    Notification::assertNothingSent();
});

it('does not reassign already assigned leads', function (): void {
    Notification::fake();

    $user = User::factory()->create();
    $this->team->users()->attach($user->id);

    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);

    LeadScore::factory()->forTeam($this->team)->create([
        'customer_id' => $customer->id,
        'score' => 80,
        'assigned_to' => $user->id,
        'assigned_at' => now(),
    ]);

    $assigned = $this->service->assignLeadsRoundRobin($this->team);

    expect($assigned)->toBe(0);

    Notification::assertNothingSent();
});
