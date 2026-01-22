<?php

declare(strict_types=1);

use App\Filament\Imports\InteractionImporter;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Interaction;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Spatie\Permission\Models\Permission;

beforeEach(function (): void {
    $this->user = User::factory()->create();

    Permission::query()->firstOrCreate(['name' => 'view_any_interaction']);
    $this->user->givePermissionTo('view_any_interaction');

    $this->actingAs($this->user);
});

it('has importer class configured correctly', function (): void {
    expect(InteractionImporter::class)->toBeString();
    expect(InteractionImporter::getColumns())->toBeArray()->not()->toBeEmpty();
});

it('has correct column mappings', function (): void {
    $columns = InteractionImporter::getColumns();
    $columnNames = array_map(fn (ImportColumn $column): string => $column->getName(), $columns);

    expect($columnNames)->toContain('customer_identifier')
        ->and($columnNames)->toContain('contact_email')
        ->and($columnNames)->toContain('user_email')
        ->and($columnNames)->toContain('type')
        ->and($columnNames)->toContain('category')
        ->and($columnNames)->toContain('channel')
        ->and($columnNames)->toContain('direction')
        ->and($columnNames)->toContain('status')
        ->and($columnNames)->toContain('subject')
        ->and($columnNames)->toContain('description')
        ->and($columnNames)->toContain('interaction_date')
        ->and($columnNames)->toContain('duration')
        ->and($columnNames)->toContain('next_action')
        ->and($columnNames)->toContain('next_action_date');
});

it('has required fields configured', function (): void {
    $columns = InteractionImporter::getColumns();

    $customerIdentifierColumn = collect($columns)->first(fn ($col): bool => $col->getName() === 'customer_identifier');
    $userEmailColumn = collect($columns)->first(fn ($col): bool => $col->getName() === 'user_email');
    $typeColumn = collect($columns)->first(fn ($col): bool => $col->getName() === 'type');
    $statusColumn = collect($columns)->first(fn ($col): bool => $col->getName() === 'status');
    $subjectColumn = collect($columns)->first(fn ($col): bool => $col->getName() === 'subject');
    $interactionDateColumn = collect($columns)->first(fn ($col): bool => $col->getName() === 'interaction_date');

    expect($customerIdentifierColumn)->not()->toBeNull();
    expect($userEmailColumn)->not()->toBeNull();
    expect($typeColumn)->not()->toBeNull();
    expect($statusColumn)->not()->toBeNull();
    expect($subjectColumn)->not()->toBeNull();
    expect($interactionDateColumn)->not()->toBeNull();
});

it('has correct model configured', function (): void {
    $reflection = new ReflectionClass(InteractionImporter::class);
    $property = $reflection->getProperty('model');

    $model = $property->getValue();

    expect($model)->toBe(Interaction::class);
});

it('has customer identifier column with correct label', function (): void {
    $columns = InteractionImporter::getColumns();
    $customerIdentifierColumn = collect($columns)->first(fn ($col): bool => $col->getName() === 'customer_identifier');

    expect($customerIdentifierColumn)->not()->toBeNull();
    expect($customerIdentifierColumn->getLabel())->toBe('Customer Identifier');
});

it('has duration column configured as numeric', function (): void {
    $columns = InteractionImporter::getColumns();
    $durationColumn = collect($columns)->first(fn ($col): bool => $col->getName() === 'duration');

    expect($durationColumn)->not()->toBeNull();

    $reflection = new ReflectionClass($durationColumn);
    $property = $reflection->getProperty('isNumeric');

    expect($property->getValue($durationColumn))->toBeTrue();
});

it('has update existing option', function (): void {
    $options = InteractionImporter::getOptionsFormComponents();

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
        ->orWhereHas('company', fn ($query) => $query->where('email', 'TEST-001'))
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
        ->orWhereHas('company', fn ($query) => $query->where('email', 'Another Customer'))
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
        ->orWhereHas('company', fn ($query) => $query->where('email', 'NON-EXISTENT'))
        ->first();

    expect($foundCustomer)->toBeNull();
});

it('can find user by email', function (): void {
    $user = User::factory()->create([
        'email' => 'user@example.com',
    ]);

    $foundUser = User::query()
        ->where('email', 'user@example.com')
        ->first();

    expect($foundUser)->not()->toBeNull();
    expect($foundUser->id)->toBe($user->id);
});

it('returns null when user not found', function (): void {
    $foundUser = User::query()
        ->where('email', 'nonexistent@example.com')
        ->first();

    expect($foundUser)->toBeNull();
});
