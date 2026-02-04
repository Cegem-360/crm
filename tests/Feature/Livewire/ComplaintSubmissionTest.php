<?php

declare(strict_types=1);

use App\Enums\ComplaintSeverity;
use App\Enums\ComplaintStatus;
use App\Enums\CustomerType;
use App\Enums\Role;
use App\Livewire\ComplaintSubmission;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\User;
use App\Notifications\NewComplaintNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function (): void {
    app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('can render the complaint submission form', function (): void {
    Livewire::test(ComplaintSubmission::class)
        ->assertSuccessful()
        ->assertSee('Submit a Complaint')
        ->assertSet('submitted', false);
});

it('can submit a complaint and create a new customer with contact', function (): void {
    Notification::fake();

    Livewire::test(ComplaintSubmission::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('phone', '+36 20 123 4567')
        ->set('title', 'Product Defect')
        ->set('description', 'The product arrived damaged and does not work properly.')
        ->call('submit')
        ->assertSet('submitted', true)
        ->assertHasNoErrors();

    $contact = CustomerContact::where('email', 'john@example.com')->first();
    expect($contact)->not->toBeNull();
    expect($contact->name)->toBe('John Doe');
    expect($contact->phone)->toBe('+36 20 123 4567');
    expect($contact->is_primary)->toBeTrue();

    $customer = $contact->customer;
    expect($customer->name)->toBe('John Doe');
    expect($customer->phone)->toBe('+36 20 123 4567');
    expect($customer->type)->toBe(CustomerType::Company);
    expect($customer->is_active)->toBeTrue();
    expect($customer->unique_identifier)->toStartWith('GUEST-');

    expect(Complaint::where('customer_id', $customer->id)->exists())->toBeTrue();

    $complaint = Complaint::where('customer_id', $customer->id)->first();
    expect($complaint->title)->toBe('Product Defect');
    expect($complaint->description)->toBe('The product arrived damaged and does not work properly.');
    expect($complaint->severity)->toBe(ComplaintSeverity::Medium);
    expect($complaint->status)->toBe(ComplaintStatus::Open);
});

it('reuses existing customer when contact email already exists', function (): void {
    Notification::fake();

    $existingCustomer = Customer::factory()->create([
        'name' => 'Existing Customer',
    ]);

    $existingCustomer->contacts()->create([
        'name' => 'Existing Contact',
        'email' => 'existing@example.com',
        'is_primary' => true,
    ]);

    Livewire::test(ComplaintSubmission::class)
        ->set('name', 'Different Name')
        ->set('email', 'existing@example.com')
        ->set('title', 'Service Issue')
        ->set('description', 'This is a detailed description of my service issue.')
        ->call('submit')
        ->assertSet('submitted', true);

    expect(CustomerContact::where('email', 'existing@example.com')->count())->toBe(1);

    $complaint = Complaint::where('customer_id', $existingCustomer->id)->first();
    expect($complaint)->not->toBeNull();
    expect($complaint->title)->toBe('Service Issue');
});

it('notifies admins and managers when complaint is submitted', function (): void {
    Notification::fake();

    SpatieRole::create(['name' => Role::Admin->value]);
    SpatieRole::create(['name' => Role::Manager->value]);
    SpatieRole::create(['name' => Role::SalesRepresentative->value]);

    $admin = User::factory()->create();
    $admin->assignRole(Role::Admin);

    $manager = User::factory()->create();
    $manager->assignRole(Role::Manager);

    $salesRep = User::factory()->create();
    $salesRep->assignRole(Role::SalesRepresentative);

    Livewire::test(ComplaintSubmission::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('title', 'Urgent Issue')
        ->set('description', 'This issue needs immediate attention please.')
        ->call('submit');

    Notification::assertSentTo([$admin, $manager], NewComplaintNotification::class);
    Notification::assertNotSentTo($salesRep, NewComplaintNotification::class);
});

it('validates required fields', function (): void {
    Livewire::test(ComplaintSubmission::class)
        ->set('name', '')
        ->set('email', '')
        ->set('title', '')
        ->set('description', '')
        ->call('submit')
        ->assertHasErrors(['name', 'email', 'title', 'description'])
        ->assertSet('submitted', false);
});

it('validates email format', function (): void {
    Livewire::test(ComplaintSubmission::class)
        ->set('name', 'John Doe')
        ->set('email', 'invalid-email')
        ->set('title', 'Test Title')
        ->set('description', 'This is a valid description with enough characters.')
        ->call('submit')
        ->assertHasErrors(['email']);
});

it('validates description minimum length', function (): void {
    Livewire::test(ComplaintSubmission::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('title', 'Test Title')
        ->set('description', 'Too short')
        ->call('submit')
        ->assertHasErrors(['description']);
});

it('resets form fields after successful submission', function (): void {
    Notification::fake();

    Livewire::test(ComplaintSubmission::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('phone', '123456789')
        ->set('title', 'Test Complaint')
        ->set('description', 'This is a detailed description of the complaint.')
        ->set('order_number', 'ORD-12345')
        ->call('submit')
        ->assertSet('name', '')
        ->assertSet('email', '')
        ->assertSet('phone', '')
        ->assertSet('title', '')
        ->assertSet('description', '')
        ->assertSet('order_number', '');
});

it('shows success message after submission', function (): void {
    Notification::fake();

    Livewire::test(ComplaintSubmission::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('title', 'Test Complaint')
        ->set('description', 'This is a detailed description of the complaint.')
        ->call('submit')
        ->assertSet('submitted', true)
        ->assertSee('Complaint Submitted Successfully');
});

it('can submit another complaint after success', function (): void {
    Notification::fake();

    $component = Livewire::test(ComplaintSubmission::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('title', 'First Complaint')
        ->set('description', 'This is the first complaint description.')
        ->call('submit')
        ->assertSet('submitted', true);

    $component->set('submitted', false)
        ->assertSet('submitted', false)
        ->assertSee('Submit a Complaint');
});

it('is accessible via the complaints submit route', function (): void {
    $response = $this->get(route('complaints.submit'));

    $response->assertSuccessful();
    $response->assertSeeLivewire(ComplaintSubmission::class);
});
