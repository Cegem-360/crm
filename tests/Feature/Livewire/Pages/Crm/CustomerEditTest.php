<?php

declare(strict_types=1);

use App\Enums\CustomerType;
use App\Livewire\Pages\Crm\Customers\EditCustomer;
use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can render create page', function () {
    $component = Livewire::test(EditCustomer::class)
        ->assertSuccessful();

    expect($component->get('customer')?->exists)->toBeFalsy();
});

it('has form component for unique identifier', function () {
    Livewire::test(EditCustomer::class)
        ->assertFormExists()
        ->assertFormFieldExists('unique_identifier');
});

it('can render edit page with existing customer', function () {
    $customer = Customer::factory()->create([
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

it('can create a customer', function () {
    $company = Company::factory()->create();

    Livewire::test(EditCustomer::class)
        ->fillForm([
            'unique_identifier' => 'CUST-TEST1234',
            'name' => 'Test Customer',
            'type' => CustomerType::Company,
            'company_id' => $company->id,
            'phone' => '+1234567890',
            'is_active' => true,
        ])
        ->call('save')
        ->assertRedirect();

    expect(Customer::where('name', 'Test Customer')->exists())->toBeTrue();
});

it('validates required fields on create', function () {
    Livewire::test(EditCustomer::class)
        ->fillForm([
            'unique_identifier' => '',
            'name' => '',
        ])
        ->call('save')
        ->assertHasFormErrors(['unique_identifier' => 'required', 'name' => 'required']);
});

it('validates unique identifier uniqueness', function () {
    Customer::factory()->create(['unique_identifier' => 'CUST-EXISTING']);

    Livewire::test(EditCustomer::class)
        ->fillForm([
            'unique_identifier' => 'CUST-EXISTING',
            'name' => 'Test Customer',
            'type' => CustomerType::Individual,
        ])
        ->call('save')
        ->assertHasFormErrors(['unique_identifier']);
});

it('can update a customer', function () {
    $customer = Customer::factory()->create([
        'name' => 'Old Name',
        'is_active' => true,
    ]);

    Livewire::test(EditCustomer::class, ['customer' => $customer])
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

it('allows same unique identifier when editing the same customer', function () {
    $customer = Customer::factory()->create(['unique_identifier' => 'CUST-EXISTING']);

    Livewire::test(EditCustomer::class, ['customer' => $customer])
        ->call('save')
        ->assertHasNoFormErrors(['unique_identifier']);
});

it('redirects to view page after save', function () {
    $customer = Customer::factory()->create();

    Livewire::test(EditCustomer::class, ['customer' => $customer])
        ->fillForm([
            'name' => 'Updated Customer',
        ])
        ->call('save')
        ->assertRedirectContains('/dashboard/customers/');
});
