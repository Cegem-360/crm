<?php

declare(strict_types=1);

use App\Enums\OpportunityStage;
use App\Filament\Resources\LeadOpportunities\Pages\ListLeadOpportunities;
use App\Models\Customer;
use App\Models\Opportunity;
use App\Models\Team;
use App\Models\User;
use Spatie\Permission\Models\Permission;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

/**
 * @property User $user
 * @property Team $team
 */
beforeEach(function (): void {
    /** @var User $this->user */
    $this->user = User::factory()->create();

    Permission::query()->firstOrCreate(['name' => 'view_any_opportunity']);
    /** @var User $this->user */
    $this->user->givePermissionTo('view_any_opportunity');

    actingAs($this->user);
    $this->team = setUpFilamentTenant($this->user);
});

it('can render lead opportunities page', function (): void {
    livewire(ListLeadOpportunities::class)
        ->assertSuccessful();
});

it('displays opportunities in the lead opportunities resource', function (): void {
    $customer = Customer::factory()->for($this->team)->create();
    $opportunity = Opportunity::factory()->for($this->team)->create([
        'customer_id' => $customer->id,
        'stage' => OpportunityStage::Lead,
        'title' => 'Lead Test Opportunity',
    ]);

    livewire(ListLeadOpportunities::class)
        ->assertCanSeeTableRecords([$opportunity]);
});

it('displays customer data in lead opportunities table', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'name' => 'Test Customer Name',
    ]);

    $opportunity = Opportunity::factory()->for($this->team)->create([
        'customer_id' => $customer->id,
        'stage' => OpportunityStage::Lead,
        'title' => 'Lead Test Opportunity',
    ]);

    livewire(ListLeadOpportunities::class)
        ->assertCanSeeTableRecords([$opportunity])
        ->assertSee('Test Customer Name');
});

it('can search lead opportunities by customer name', function (): void {
    $customer1 = Customer::factory()->for($this->team)->create(['name' => 'John Doe']);
    $customer2 = Customer::factory()->for($this->team)->create(['name' => 'Jane Smith']);

    $opportunity1 = Opportunity::factory()->for($this->team)->create([
        'customer_id' => $customer1->id,
        'stage' => OpportunityStage::Lead,
        'title' => 'First Opportunity',
    ]);

    $opportunity2 = Opportunity::factory()->for($this->team)->create([
        'customer_id' => $customer2->id,
        'stage' => OpportunityStage::Lead,
        'title' => 'Second Opportunity',
    ]);

    livewire(ListLeadOpportunities::class)
        ->searchTable('John Doe')
        ->assertCanSeeTableRecords([$opportunity1])
        ->assertCanNotSeeTableRecords([$opportunity2]);
});

it('can search lead opportunities by opportunity title', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    $opportunity1 = Opportunity::factory()->for($this->team)->create([
        'customer_id' => $customer->id,
        'stage' => OpportunityStage::Lead,
        'title' => 'Unique Opportunity Title',
    ]);

    $opportunity2 = Opportunity::factory()->for($this->team)->create([
        'customer_id' => $customer->id,
        'stage' => OpportunityStage::Lead,
        'title' => 'Different Opportunity',
    ]);

    livewire(ListLeadOpportunities::class)
        ->searchTable('Unique Opportunity')
        ->assertCanSeeTableRecords([$opportunity1])
        ->assertCanNotSeeTableRecords([$opportunity2]);
});

it('can sort lead opportunities by customer name', function (): void {
    $customer1 = Customer::factory()->for($this->team)->create(['name' => 'Alpha Customer']);
    $customer2 = Customer::factory()->for($this->team)->create(['name' => 'Zulu Customer']);

    $opportunity1 = Opportunity::factory()->for($this->team)->create([
        'customer_id' => $customer1->id,
        'stage' => OpportunityStage::Lead,
    ]);

    $opportunity2 = Opportunity::factory()->for($this->team)->create([
        'customer_id' => $customer2->id,
        'stage' => OpportunityStage::Lead,
    ]);

    livewire(ListLeadOpportunities::class)
        ->sortTable('customer.name')
        ->assertCanSeeTableRecords([$opportunity1, $opportunity2])
        ->sortTable('customer.name', 'desc')
        ->assertCanSeeTableRecords([$opportunity2, $opportunity1]);
});

it('can sort lead opportunities by value', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    $opportunity1 = Opportunity::factory()->for($this->team)->create([
        'customer_id' => $customer->id,
        'stage' => OpportunityStage::Lead,
        'value' => 1000,
    ]);

    $opportunity2 = Opportunity::factory()->for($this->team)->create([
        'customer_id' => $customer->id,
        'stage' => OpportunityStage::Lead,
        'value' => 5000,
    ]);

    livewire(ListLeadOpportunities::class)
        ->sortTable('value')
        ->assertCanSeeTableRecords([$opportunity1, $opportunity2])
        ->sortTable('value', 'desc')
        ->assertCanSeeTableRecords([$opportunity2, $opportunity1]);
});

it('displays correct table columns for lead opportunities', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'name' => 'Test Customer',
    ]);

    $user = User::factory()->create(['name' => 'Assigned User']);
    $user->teams()->syncWithoutDetaching($this->team);

    $opportunity = Opportunity::factory()->for($this->team)->create([
        'customer_id' => $customer->id,
        'stage' => OpportunityStage::Lead,
        'title' => 'Test Opportunity',
        'value' => 10000,
        'probability' => 75,
        'assigned_to' => $user->id,
    ]);

    livewire(ListLeadOpportunities::class)
        ->assertCanSeeTableRecords([$opportunity])
        ->assertSee('Test Customer')
        ->assertSee('Test Opportunity')
        ->assertSee('75%')
        ->assertSee('Assigned User');
});
