<?php

declare(strict_types=1);

use App\Enums\CustomFieldModel;
use App\Enums\CustomFieldType;
use App\Filament\Resources\CustomFields\Pages\CreateCustomField;
use App\Filament\Resources\CustomFields\Pages\EditCustomField;
use App\Filament\Resources\CustomFields\Pages\ListCustomFields;
use App\Models\CustomField;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);

    $this->team = setUpFilamentTenant($this->user);
});

it('can render custom field list page', function (): void {
    livewire(ListCustomFields::class)
        ->assertSuccessful();
});

it('can list custom fields', function (): void {
    $customFields = CustomField::factory()->count(3)->create();

    livewire(ListCustomFields::class)
        ->assertCanSeeTableRecords($customFields);
});

it('can render create custom field page', function (): void {
    livewire(CreateCustomField::class)
        ->assertSuccessful();
});

it('can create a text custom field', function (): void {
    livewire(CreateCustomField::class)
        ->fillForm([
            'name' => 'Test Field',
            'type' => CustomFieldType::Text->value,
            'model_type' => CustomFieldModel::Customer->value,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(CustomField::class, [
        'name' => 'Test Field',
        'type' => CustomFieldType::Text->value,
        'model_type' => CustomFieldModel::Customer->value,
    ]);
});

it('can create a number custom field', function (): void {
    livewire(CreateCustomField::class)
        ->fillForm([
            'name' => 'Quantity Field',
            'type' => CustomFieldType::Number->value,
            'model_type' => CustomFieldModel::Order->value,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(CustomField::class, [
        'name' => 'Quantity Field',
        'type' => CustomFieldType::Number->value,
        'model_type' => CustomFieldModel::Order->value,
    ]);
});

it('can create a select custom field with options', function (): void {
    $customField = CustomField::factory()->select()->forTask()->create([
        'name' => 'Priority Level',
        'options' => ['Low', 'Medium', 'High'],
    ]);

    expect($customField->options)->toBe(['Low', 'Medium', 'High']);

    livewire(EditCustomField::class, ['record' => $customField->id])
        ->assertFormFieldVisible('options')
        ->assertFormSet([
            'options' => ['Low', 'Medium', 'High'],
        ]);
});

it('can render edit custom field page', function (): void {
    $customField = CustomField::factory()->create();

    livewire(EditCustomField::class, ['record' => $customField->id])
        ->assertSuccessful();
});

it('can edit a custom field', function (): void {
    $customField = CustomField::factory()->create([
        'name' => 'Original Name',
    ]);

    livewire(EditCustomField::class, ['record' => $customField->id])
        ->fillForm([
            'name' => 'Updated Name',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($customField->fresh()->name)->toBe('Updated Name');
});

it('validates that `name` is required', function (): void {
    livewire(CreateCustomField::class)
        ->fillForm([
            'name' => '',
            'type' => CustomFieldType::Text->value,
            'model_type' => CustomFieldModel::Customer->value,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

it('validates that `type` is required', function (): void {
    livewire(CreateCustomField::class)
        ->fillForm([
            'name' => 'Test Field',
            'type' => '',
            'model_type' => CustomFieldModel::Customer->value,
        ])
        ->call('create')
        ->assertHasFormErrors(['type' => 'required']);
});

it('validates that `model_type` is required', function (): void {
    livewire(CreateCustomField::class)
        ->fillForm([
            'name' => 'Test Field',
            'type' => CustomFieldType::Text->value,
            'model_type' => '',
        ])
        ->call('create')
        ->assertHasFormErrors(['model_type' => 'required']);
});

it('can filter by model type', function (): void {
    $customerField = CustomField::factory()->forCustomer()->create();
    $orderField = CustomField::factory()->forOrder()->create();

    livewire(ListCustomFields::class)
        ->filterTable('model_type', CustomFieldModel::Customer->value)
        ->assertCanSeeTableRecords([$customerField])
        ->assertCanNotSeeTableRecords([$orderField]);
});

it('can filter by field type', function (): void {
    $textField = CustomField::factory()->text()->create();
    $checkboxField = CustomField::factory()->checkbox()->create();

    livewire(ListCustomFields::class)
        ->filterTable('type', CustomFieldType::Text->value)
        ->assertCanSeeTableRecords([$textField])
        ->assertCanNotSeeTableRecords([$checkboxField]);
});
