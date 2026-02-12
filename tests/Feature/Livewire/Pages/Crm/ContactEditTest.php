<?php

declare(strict_types=1);

use App\Livewire\Pages\Crm\Contacts\EditContact;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->team = setUpFrontendTenant($this->user);
});

it('can render create page', function (): void {
    $component = Livewire::test(EditContact::class)
        ->assertSuccessful();

    expect($component->get('contact')?->exists)->toBeFalsy();
});

it('can render edit page with existing contact', function (): void {
    $customer = Customer::factory()->for($this->team)->create();
    $contact = CustomerContact::factory()->for($customer)->create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'is_primary' => true,
    ]);

    Livewire::test(EditContact::class, ['contact' => $contact])
        ->assertSuccessful()
        ->assertFormSet([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'is_primary' => true,
        ]);
});

it('can create a contact', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    Livewire::test(EditContact::class)
        ->set('team', $this->team)
        ->fillForm([
            'customer_id' => $customer->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'position' => 'Manager',
            'is_primary' => true,
        ])
        ->call('save')
        ->assertRedirect();

    expect(CustomerContact::query()->where('name', 'John Doe')->exists())->toBeTrue();
});

it('validates required fields on create', function (): void {
    Livewire::test(EditContact::class)
        ->fillForm([
            'customer_id' => null,
            'name' => '',
        ])
        ->call('save')
        ->assertHasFormErrors(['customer_id' => 'required', 'name' => 'required']);
});

it('validates email format', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    Livewire::test(EditContact::class)
        ->fillForm([
            'customer_id' => $customer->id,
            'name' => 'John Doe',
            'email' => 'not-an-email',
        ])
        ->call('save')
        ->assertHasFormErrors(['email']);
});

it('can update a contact', function (): void {
    $customer = Customer::factory()->for($this->team)->create();
    $contact = CustomerContact::factory()->for($customer)->create([
        'name' => 'Old Name',
        'is_primary' => false,
    ]);

    Livewire::test(EditContact::class, ['contact' => $contact])
        ->set('team', $this->team)
        ->fillForm([
            'name' => 'New Name',
            'is_primary' => true,
        ])
        ->call('save')
        ->assertRedirect();

    $contact->refresh();
    expect($contact->name)->toBe('New Name');
    expect($contact->is_primary)->toBeTrue();
});

it('redirects to view page after save', function (): void {
    $customer = Customer::factory()->for($this->team)->create();
    $contact = CustomerContact::factory()->for($customer)->create();

    Livewire::test(EditContact::class, ['contact' => $contact])
        ->set('team', $this->team)
        ->fillForm([
            'name' => 'Updated Contact',
        ])
        ->call('save')
        ->assertRedirectContains('/contacts/');
});
