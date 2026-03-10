<?php

declare(strict_types=1);

use App\Enums\CustomFieldModel;
use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Models\Customer;
use App\Models\CustomField;
use App\Models\CustomFieldValue;
use App\Models\User;
use App\Services\CustomFieldService;
use Spatie\Permission\Models\Permission;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();

    Permission::query()->firstOrCreate(['name' => 'view_any_customer']);
    Permission::query()->firstOrCreate(['name' => 'view_customer']);
    Permission::query()->firstOrCreate(['name' => 'create_customer']);
    Permission::query()->firstOrCreate(['name' => 'update_customer']);
    Permission::query()->firstOrCreate(['name' => 'delete_customer']);

    $this->user->givePermissionTo([
        'view_any_customer',
        'view_customer',
        'create_customer',
        'update_customer',
        'delete_customer',
    ]);

    $this->actingAs($this->user);

    $this->team = setUpFilamentTenant($this->user);
});

it('can render customer list page', function (): void {
    livewire(ListCustomers::class)
        ->assertSuccessful();
});

it('can list customers', function (): void {
    $customers = Customer::factory()->count(3)->for($this->team)->create();

    livewire(ListCustomers::class)
        ->assertCanSeeTableRecords($customers);
});

it('can search customers by name', function (): void {
    $customers = Customer::factory()->count(3)->for($this->team)->create();
    $customerToFind = $customers->first();

    livewire(ListCustomers::class)
        ->searchTable($customerToFind->name)
        ->assertCanSeeTableRecords([$customerToFind])
        ->assertCanNotSeeTableRecords($customers->skip(1));
});

it('can render create customer page', function (): void {
    livewire(CreateCustomer::class)
        ->assertSuccessful();
});

it('can render edit customer page', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    livewire(EditCustomer::class, ['record' => $customer->id])
        ->assertSuccessful();
});

it('can retrieve customer data for editing', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    livewire(EditCustomer::class, ['record' => $customer->id])
        ->assertSchemaStateSet([
            'unique_identifier' => $customer->unique_identifier,
            'name' => $customer->name,
            'type' => $customer->type,
            'email' => $customer->email,
            'phone' => $customer->phone,
        ]);
});

it('can delete a customer', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    livewire(EditCustomer::class, ['record' => $customer->id])
        ->callAction('delete');

    $this->assertSoftDeleted($customer);
});

it('cannot access list page without permission', function (): void {
    $user = User::factory()->create();
    $user->teams()->syncWithoutDetaching([$this->team->id]);
    $this->actingAs($user);

    livewire(ListCustomers::class)
        ->assertForbidden();
});

it('cannot access create page without permission', function (): void {
    $user = User::factory()->create();
    $user->teams()->syncWithoutDetaching([$this->team->id]);
    $this->actingAs($user);

    livewire(CreateCustomer::class)
        ->assertForbidden();
});

it('can see create opportunity action on edit page', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    livewire(EditCustomer::class, ['record' => $customer->id])
        ->assertActionVisible('create_opportunity');
});

it('cannot access edit page without permission', function (): void {
    $customer = Customer::factory()->for($this->team)->create();
    $user = User::factory()->create();
    $user->teams()->syncWithoutDetaching([$this->team->id]);
    $this->actingAs($user);

    livewire(EditCustomer::class, ['record' => $customer->id])
        ->assertForbidden();
});

it('can save custom field values when editing a customer', function (): void {
    $customField = CustomField::factory()->forCustomer()->text()->create([
        'slug' => 'test-field',
        'name' => 'Test Field',
    ]);

    resolve(CustomFieldService::class)->clearCache(CustomFieldModel::Customer);

    $customer = Customer::factory()->for($this->team)->create();

    livewire(EditCustomer::class, ['record' => $customer->id])
        ->fillForm([
            'custom_fields.test-field' => 'hello world',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $savedValue = CustomFieldValue::query()
        ->where('model_type', 'customer')
        ->where('model_id', $customer->id)
        ->where('custom_field_id', $customField->id)
        ->first();

    expect($savedValue)->not->toBeNull()
        ->and($savedValue->value)->toBe('hello world');
});

it('can save custom field values when creating a customer', function (): void {
    $customField = CustomField::factory()->forCustomer()->text()->create([
        'slug' => 'create-test-field',
        'name' => 'Create Test Field',
    ]);

    resolve(CustomFieldService::class)->clearCache(CustomFieldModel::Customer);

    livewire(CreateCustomer::class)
        ->fillForm([
            'name' => 'Test Customer',
            'type' => 'individual',
            'is_active' => true,
            'custom_fields.create-test-field' => 'created value',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $customer = Customer::query()->where('name', 'Test Customer')->first();
    expect($customer)->not->toBeNull();

    $savedValue = CustomFieldValue::query()
        ->where('model_type', 'customer')
        ->where('model_id', $customer->id)
        ->where('custom_field_id', $customField->id)
        ->first();

    expect($savedValue)->not->toBeNull()
        ->and($savedValue->value)->toBe('created value');
});

it('can update existing custom field values', function (): void {
    $customField = CustomField::factory()->forCustomer()->text()->create([
        'slug' => 'update-test-field',
        'name' => 'Update Test Field',
    ]);

    resolve(CustomFieldService::class)->clearCache(CustomFieldModel::Customer);

    $customer = Customer::factory()->for($this->team)->create();
    $customer->setCustomFieldValue('update-test-field', 'original value');

    livewire(EditCustomer::class, ['record' => $customer->id])
        ->assertSchemaStateSet([
            'custom_fields.update-test-field' => 'original value',
        ])
        ->fillForm([
            'custom_fields.update-test-field' => 'updated value',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $savedValue = CustomFieldValue::query()
        ->where('model_type', 'customer')
        ->where('model_id', $customer->id)
        ->where('custom_field_id', $customField->id)
        ->first();

    expect($savedValue)->not->toBeNull()
        ->and($savedValue->value)->toBe('updated value');
});
