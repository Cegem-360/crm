<?php

declare(strict_types=1);

use App\Filament\Imports\CustomerContactImporter;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Spatie\Permission\Models\Permission;

beforeEach(function (): void {
    $this->user = User::factory()->create();

    Permission::query()->firstOrCreate(['name' => 'view_any_contact']);
    $this->user->givePermissionTo('view_any_contact');

    $this->actingAs($this->user);
});

it('has importer class configured correctly', function (): void {
    expect(CustomerContactImporter::class)->toBeString();
    expect(CustomerContactImporter::getColumns())->toBeArray()->not()->toBeEmpty();
});

it('has correct column mappings', function (): void {
    $columns = CustomerContactImporter::getColumns();
    $columnNames = array_map(fn (ImportColumn $column): string => $column->getName(), $columns);

    expect($columnNames)->toContain('customer_identifier')
        ->and($columnNames)->toContain('name')
        ->and($columnNames)->toContain('email')
        ->and($columnNames)->toContain('phone')
        ->and($columnNames)->toContain('position')
        ->and($columnNames)->toContain('is_primary');
});

it('has required fields configured', function (): void {
    $columns = CustomerContactImporter::getColumns();

    $customerIdentifierColumn = collect($columns)->first(fn ($col): bool => $col->getName() === 'customer_identifier');
    $nameColumn = collect($columns)->first(fn ($col): bool => $col->getName() === 'name');

    expect($customerIdentifierColumn)->not()->toBeNull();
    expect($nameColumn)->not()->toBeNull();
});

it('has correct model configured', function (): void {
    $reflection = new ReflectionClass(CustomerContactImporter::class);
    $property = $reflection->getProperty('model');

    $model = $property->getValue();

    expect($model)->toBe(CustomerContact::class);
});

it('has customer identifier column with correct label', function (): void {
    $columns = CustomerContactImporter::getColumns();
    $customerIdentifierColumn = collect($columns)->first(fn ($col): bool => $col->getName() === 'customer_identifier');

    expect($customerIdentifierColumn)->not()->toBeNull();
    expect($customerIdentifierColumn->getLabel())->toBe('Customer Identifier');
});

it('has is_primary column configured as boolean', function (): void {
    $columns = CustomerContactImporter::getColumns();
    $isPrimaryColumn = collect($columns)->first(fn ($col): bool => $col->getName() === 'is_primary');

    expect($isPrimaryColumn)->not()->toBeNull();

    $reflection = new ReflectionClass($isPrimaryColumn);
    $property = $reflection->getProperty('isBoolean');

    expect($property->getValue($isPrimaryColumn))->toBeTrue();
});

it('has update existing option', function (): void {
    $options = CustomerContactImporter::getOptionsFormComponents();

    expect($options)->toBeArray()->not()->toBeEmpty();

    $checkboxFound = collect($options)->contains(fn ($component): bool => $component->getName() === 'updateExisting');

    expect($checkboxFound)->toBeTrue();
});

it('can find customer by unique identifier', function (): void {
    $customer = Customer::factory()->create([
        'unique_identifier' => 'TEST-001',
        'name' => 'Test Customer',
    ]);

    $foundCustomer = Customer::query()
        ->where('unique_identifier', 'TEST-001')
        ->orWhere('name', 'TEST-001')
        ->orWhere('email', 'TEST-001')
        ->first();

    expect($foundCustomer)->not()->toBeNull();
    expect($foundCustomer->id)->toBe($customer->id);
});

it('can find customer by name', function (): void {
    $customer = Customer::factory()->create([
        'unique_identifier' => 'TEST-002',
        'name' => 'Another Customer',
    ]);

    $foundCustomer = Customer::query()
        ->where('unique_identifier', 'Another Customer')
        ->orWhere('name', 'Another Customer')
        ->orWhere('email', 'Another Customer')
        ->first();

    expect($foundCustomer)->not()->toBeNull();
    expect($foundCustomer->id)->toBe($customer->id);
});

it('can find customer by company email', function (): void {
    $company = Company::factory()->create([
        'email' => 'company@example.com',
    ]);

    $customer = Customer::factory()->create([
        'unique_identifier' => 'TEST-003',
        'name' => 'Email Customer',
        'company_id' => $company->id,
    ]);

    $foundCustomer = Customer::query()
        ->where('unique_identifier', 'company@example.com')
        ->orWhere('name', 'company@example.com')
        ->orWhereHas('company', fn ($query) => $query->where('email', 'company@example.com'))
        ->first();

    expect($foundCustomer)->not()->toBeNull();
    expect($foundCustomer->id)->toBe($customer->id);
});

it('returns null when customer not found', function (): void {
    $foundCustomer = Customer::query()
        ->where('unique_identifier', 'NON-EXISTENT')
        ->orWhere('name', 'NON-EXISTENT')
        ->orWhere('email', 'NON-EXISTENT')
        ->first();

    expect($foundCustomer)->toBeNull();
});
