<?php

declare(strict_types=1);

use App\Enums\CustomerType;
use App\Livewire\Pages\Crm\Customers\EditCustomer;
use App\Models\Customer;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->team = setUpFrontendTenant($this->user);
});

it('can render create page', function (): void {
    $component = Livewire::test(EditCustomer::class)
        ->assertSuccessful();

    expect($component->get('customer')?->exists)->toBeFalsy();
});

it('has form component for unique identifier', function (): void {
    Livewire::test(EditCustomer::class)
        ->assertFormExists()
        ->assertFormFieldExists('unique_identifier');
});

it('can render edit page with existing customer', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'name' => 'Existing Customer',
        'type' => CustomerType::Company,
        'is_active' => false,
    ]);

    Livewire::test(EditCustomer::class, ['customer' => $customer])
        ->assertSuccessful()
        ->assertFormSet([
            'name' => 'Existing Customer',
            'type' => CustomerType::Company,
            'is_active' => false,
        ]);
});

it('can create a customer', function (): void {
    Livewire::test(EditCustomer::class)
        ->set('team', $this->team)
        ->fillForm([
            'name' => 'Test Customer',
            'type' => CustomerType::Company,
            'phone' => '+1234567890',
            'is_active' => true,
        ])
        ->call('save')
        ->assertRedirect();

    expect(Customer::query()->where('name', 'Test Customer')->exists())->toBeTrue();
});

it('validates required fields on create', function (): void {
    Livewire::test(EditCustomer::class)
        ->fillForm([
            'name' => '',
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

it('auto-generates unique identifier on create', function (): void {
    Livewire::test(EditCustomer::class)
        ->set('team', $this->team)
        ->fillForm([
            'name' => 'Auto ID Customer',
            'type' => CustomerType::Individual,
            'is_active' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $customer = Customer::query()->where('name', 'Auto ID Customer')->first();
    expect($customer)->not->toBeNull()
        ->and($customer->unique_identifier)->toStartWith('CUST-');
});

it('can update a customer', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'name' => 'Old Name',
        'is_active' => true,
    ]);

    Livewire::test(EditCustomer::class, ['customer' => $customer])
        ->set('team', $this->team)
        ->fillForm([
            'name' => 'New Name',
            'is_active' => false,
        ])
        ->call('save')
        ->assertRedirect();

    $customer->refresh();
    expect($customer->name)->toBe('New Name');
    expect($customer->is_active)->toBeFalse();
});

it('allows same unique identifier when editing the same customer', function (): void {
    $customer = Customer::factory()->for($this->team)->create(['unique_identifier' => 'CUST-EXISTING']);

    Livewire::test(EditCustomer::class, ['customer' => $customer])
        ->set('team', $this->team)
        ->call('save')
        ->assertHasNoFormErrors(['unique_identifier']);
});

it('redirects to view page after save', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    Livewire::test(EditCustomer::class, ['customer' => $customer])
        ->set('team', $this->team)
        ->fillForm([
            'name' => 'Updated Customer',
        ])
        ->call('save')
        ->assertRedirectContains('/customers/');
});

it('displays relation managers for existing customers', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    Livewire::test(EditCustomer::class, ['customer' => $customer])
        ->assertSuccessful()
        ->assertSee('Contacts')
        ->assertSee('Addresses');
});

it('does not display relation managers for new customers', function (): void {
    Livewire::test(EditCustomer::class)
        ->assertSuccessful()
        ->assertDontSee('Contacts')
        ->assertDontSee('Addresses');
});

it('shows duplicate warning when name matches existing customer', function (): void {
    Customer::factory()->for($this->team)->create([
        'name' => 'Acme Corporation',
        'unique_identifier' => 'CUST-0001',
    ]);

    Livewire::test(EditCustomer::class)
        ->fillForm([
            'name' => 'Acme Corporation',
        ])
        ->assertSee('CUST-0001');
});

it('shows duplicate warning when email matches existing customer', function (): void {
    Customer::factory()->for($this->team)->create([
        'name' => 'Existing Customer',
        'email' => 'duplicate@example.com',
        'unique_identifier' => 'CUST-0002',
    ]);

    Livewire::test(EditCustomer::class)
        ->fillForm([
            'name' => 'New Customer',
            'email' => 'duplicate@example.com',
        ])
        ->assertSee('CUST-0002');
});

it('shows duplicate warning when phone matches existing customer', function (): void {
    Customer::factory()->for($this->team)->create([
        'name' => 'Phone Customer',
        'phone' => '+36301234567',
        'unique_identifier' => 'CUST-0003',
    ]);

    Livewire::test(EditCustomer::class)
        ->fillForm([
            'name' => 'Different Name',
            'phone' => '+36301234567',
        ])
        ->assertSee('CUST-0003');
});

it('does not show duplicate warning for the customer being edited', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'name' => 'Self Customer',
        'email' => 'self@example.com',
        'unique_identifier' => 'CUST-0004',
    ]);

    Livewire::test(EditCustomer::class, ['customer' => $customer])
        ->assertDontSee('Potential duplicates found')
        ->assertDontSee('Lehetséges duplikátumok');
});

it('does not show duplicate warning when no matches exist', function (): void {
    Livewire::test(EditCustomer::class)
        ->fillForm([
            'name' => 'Completely Unique Name',
            'email' => 'unique@example.com',
        ])
        ->assertDontSee('Potential duplicates found')
        ->assertDontSee('Lehetséges duplikátumok');
});
