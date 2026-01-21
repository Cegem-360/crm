<?php

declare(strict_types=1);

use App\Livewire\Pages\Crm\Contacts\EditContact;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can render create page', function () {
    $component = Livewire::test(EditContact::class)
        ->assertSuccessful();

    expect($component->get('contact')?->exists)->toBeFalsy();
});

it('can render edit page with existing contact', function () {
    $contact = CustomerContact::factory()->create([
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

it('can create a contact', function () {
    $customer = Customer::factory()->create();

    Livewire::test(EditContact::class)
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

    expect(CustomerContact::where('name', 'John Doe')->exists())->toBeTrue();
});

it('validates required fields on create', function () {
    Livewire::test(EditContact::class)
        ->fillForm([
            'customer_id' => null,
            'name' => '',
        ])
        ->call('save')
        ->assertHasFormErrors(['customer_id' => 'required', 'name' => 'required']);
});

it('validates email format', function () {
    $customer = Customer::factory()->create();

    Livewire::test(EditContact::class)
        ->fillForm([
            'customer_id' => $customer->id,
            'name' => 'John Doe',
            'email' => 'not-an-email',
        ])
        ->call('save')
        ->assertHasFormErrors(['email']);
});

it('can update a contact', function () {
    $contact = CustomerContact::factory()->create([
        'name' => 'Old Name',
        'is_primary' => false,
    ]);

    Livewire::test(EditContact::class, ['contact' => $contact])
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

it('redirects to view page after save', function () {
    $contact = CustomerContact::factory()->create();

    Livewire::test(EditContact::class, ['contact' => $contact])
        ->fillForm([
            'name' => 'Updated Contact',
        ])
        ->call('save')
        ->assertRedirectContains('/dashboard/contacts/');
});
