<?php

declare(strict_types=1);

use App\Enums\ConsentType;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Models\Customer;
use App\Models\CustomerConsent;
use App\Models\User;
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

it('can render edit customer page with GDPR actions', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    livewire(EditCustomer::class, ['record' => $customer->id])
        ->assertSuccessful()
        ->assertActionExists('export_customer_data')
        ->assertActionExists('anonymize_customer');
});

it('can create a consent record for a customer', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    $consent = CustomerConsent::query()->create([
        'team_id' => $this->team->id,
        'customer_id' => $customer->id,
        'granted_by' => $this->user->id,
        'type' => ConsentType::Marketing,
        'is_granted' => true,
        'granted_at' => now(),
        'ip_address' => '127.0.0.1',
    ]);

    expect($consent->exists)->toBeTrue()
        ->and($consent->type)->toBe(ConsentType::Marketing)
        ->and($consent->is_granted)->toBeTrue()
        ->and($consent->customer->id)->toBe($customer->id);
});

it('can revoke a consent', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    $consent = CustomerConsent::query()->create([
        'team_id' => $this->team->id,
        'customer_id' => $customer->id,
        'granted_by' => $this->user->id,
        'type' => ConsentType::Newsletter,
        'is_granted' => true,
        'granted_at' => now(),
    ]);

    $consent->update([
        'is_granted' => false,
        'revoked_at' => now(),
    ]);

    expect($consent->fresh()->is_granted)->toBeFalse()
        ->and($consent->fresh()->revoked_at)->not->toBeNull();
});

it('can list consents for a customer', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    CustomerConsent::factory()
        ->count(3)
        ->granted()
        ->create([
            'team_id' => $this->team->id,
            'customer_id' => $customer->id,
        ]);

    expect($customer->consents)->toHaveCount(3);
});

it('anonymizes customer data correctly', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+36201234567',
        'tax_number' => '12345678-1-23',
        'registration_number' => '01-09-123456',
    ]);

    $customer->contacts()->create([
        'name' => 'John Contact',
        'email' => 'contact@example.com',
        'is_primary' => true,
    ]);

    CustomerConsent::query()->create([
        'team_id' => $this->team->id,
        'customer_id' => $customer->id,
        'type' => ConsentType::Marketing,
        'is_granted' => true,
        'granted_at' => now(),
    ]);

    // Perform anonymization
    $customer->update([
        'name' => 'Anonymized Customer #'.$customer->id,
        'email' => null,
        'phone' => null,
        'tax_number' => null,
        'registration_number' => null,
        'is_active' => false,
    ]);

    $customer->contacts()->delete();
    $customer->consents()->update([
        'is_granted' => false,
        'revoked_at' => now(),
    ]);

    $customer->refresh();

    expect($customer->name)->toStartWith('Anonymized Customer')
        ->and($customer->email)->toBeNull()
        ->and($customer->phone)->toBeNull()
        ->and($customer->tax_number)->toBeNull()
        ->and($customer->registration_number)->toBeNull()
        ->and($customer->is_active)->toBeFalse()
        ->and($customer->contacts)->toHaveCount(0)
        ->and($customer->consents->first()->is_granted)->toBeFalse();
});

it('exports customer data in correct structure', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'name' => 'Export Test',
        'email' => 'export@test.com',
    ]);

    $customer->consents()->create([
        'team_id' => $this->team->id,
        'type' => ConsentType::DataProcessing,
        'is_granted' => true,
        'granted_at' => now(),
    ]);

    $customer->load(['contacts', 'addresses', 'consents', 'interactions', 'quotes', 'orders', 'invoices']);

    expect($customer->name)->toBe('Export Test')
        ->and($customer->consents)->toHaveCount(1)
        ->and($customer->consents->first()->type)->toBe(ConsentType::DataProcessing);
});
