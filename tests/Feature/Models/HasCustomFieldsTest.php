<?php

declare(strict_types=1);

use App\Enums\CustomFieldModel;
use App\Enums\CustomFieldType;
use App\Models\Customer;
use App\Models\CustomField;
use App\Models\Team;
use Carbon\Carbon;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
});

it('can set and get a text custom field value', function (): void {
    CustomField::factory()->create([
        'type' => CustomFieldType::Text,
        'model_type' => CustomFieldModel::Customer,
        'slug' => 'favorite-color',
    ]);

    $customer = Customer::factory()->for($this->team)->create();

    $customer->setCustomFieldValue('favorite-color', 'Blue');

    expect($customer->getCustomFieldValue('favorite-color'))->toBe('Blue');
});

it('can set and get a number custom field value', function (): void {
    CustomField::factory()->create([
        'type' => CustomFieldType::Number,
        'model_type' => CustomFieldModel::Customer,
        'slug' => 'loyalty-points',
    ]);

    $customer = Customer::factory()->for($this->team)->create();

    $customer->setCustomFieldValue('loyalty-points', 150);

    expect($customer->getCustomFieldValue('loyalty-points'))->toBe(150.0);
});

it('can set and get a checkbox custom field value', function (): void {
    CustomField::factory()->create([
        'type' => CustomFieldType::Checkbox,
        'model_type' => CustomFieldModel::Customer,
        'slug' => 'newsletter-subscribed',
    ]);

    $customer = Customer::factory()->for($this->team)->create();

    $customer->setCustomFieldValue('newsletter-subscribed', true);

    expect($customer->getCustomFieldValue('newsletter-subscribed'))->toBeTrue();
});

it('can set and get a date custom field value', function (): void {
    CustomField::factory()->create([
        'type' => CustomFieldType::Date,
        'model_type' => CustomFieldModel::Customer,
        'slug' => 'contract-start-date',
    ]);

    $customer = Customer::factory()->for($this->team)->create();

    $customer->setCustomFieldValue('contract-start-date', '2024-06-15');

    $value = $customer->getCustomFieldValue('contract-start-date');

    expect($value)->toBeInstanceOf(Carbon::class);
    expect($value->format('Y-m-d'))->toBe('2024-06-15');
});

it('can set and get a select custom field value', function (): void {
    CustomField::factory()->create([
        'type' => CustomFieldType::Select,
        'model_type' => CustomFieldModel::Customer,
        'slug' => 'customer-tier',
        'options' => ['Bronze', 'Silver', 'Gold'],
    ]);

    $customer = Customer::factory()->for($this->team)->create();

    $customer->setCustomFieldValue('customer-tier', 'Gold');

    expect($customer->getCustomFieldValue('customer-tier'))->toBe('Gold');
});

it('can save multiple custom field values at once', function (): void {
    CustomField::factory()->create([
        'type' => CustomFieldType::Text,
        'model_type' => CustomFieldModel::Customer,
        'slug' => 'field-one',
    ]);

    CustomField::factory()->create([
        'type' => CustomFieldType::Number,
        'model_type' => CustomFieldModel::Customer,
        'slug' => 'field-two',
    ]);

    $customer = Customer::factory()->for($this->team)->create();

    $customer->saveCustomFieldValues([
        'field-one' => 'Value One',
        'field-two' => 42,
    ]);

    expect($customer->getCustomFieldValue('field-one'))->toBe('Value One');
    expect($customer->getCustomFieldValue('field-two'))->toBe(42.0);
});

it('returns `null` for non-existent custom field values', function (): void {
    CustomField::factory()->create([
        'type' => CustomFieldType::Text,
        'model_type' => CustomFieldModel::Customer,
        'slug' => 'some-field',
    ]);

    $customer = Customer::factory()->for($this->team)->create();

    expect($customer->getCustomFieldValue('some-field'))->toBeNull();
});

it('can update an existing custom field value', function (): void {
    CustomField::factory()->create([
        'type' => CustomFieldType::Text,
        'model_type' => CustomFieldModel::Customer,
        'slug' => 'updatable-field',
    ]);

    $customer = Customer::factory()->for($this->team)->create();

    $customer->setCustomFieldValue('updatable-field', 'Original Value');

    expect($customer->getCustomFieldValue('updatable-field'))->toBe('Original Value');

    $customer->setCustomFieldValue('updatable-field', 'Updated Value');
    expect($customer->fresh()->getCustomFieldValue('updatable-field'))->toBe('Updated Value');

    // Should only have one value record
    expect($customer->customFieldValues()->count())->toBe(1);
});

it('can retrieve all custom fields for a model type', function (): void {
    CustomField::factory()->count(3)->create([
        'model_type' => CustomFieldModel::Customer,
        'is_active' => true,
    ]);

    CustomField::factory()->count(2)->create([
        'model_type' => CustomFieldModel::Order,
        'is_active' => true,
    ]);

    $customerFields = Customer::getCustomFields();

    expect($customerFields)->toHaveCount(3);
});

it('excludes inactive custom fields by default', function (): void {
    CustomField::factory()->count(2)->create([
        'model_type' => CustomFieldModel::Customer,
        'is_active' => true,
    ]);

    CustomField::factory()->create([
        'model_type' => CustomFieldModel::Customer,
        'is_active' => false,
    ]);

    $customerFields = Customer::getCustomFields();

    expect($customerFields)->toHaveCount(2);
});

it('can include inactive custom fields when requested', function (): void {
    CustomField::factory()->count(2)->create([
        'model_type' => CustomFieldModel::Customer,
        'is_active' => true,
    ]);

    CustomField::factory()->create([
        'model_type' => CustomFieldModel::Customer,
        'is_active' => false,
    ]);

    $customerFields = Customer::getCustomFields(activeOnly: false);

    expect($customerFields)->toHaveCount(3);
});

it('returns custom fields ordered by `sort_order`', function (): void {
    $fieldC = CustomField::factory()->create([
        'model_type' => CustomFieldModel::Customer,
        'name' => 'Field C',
        'sort_order' => 30,
    ]);

    $fieldA = CustomField::factory()->create([
        'model_type' => CustomFieldModel::Customer,
        'name' => 'Field A',
        'sort_order' => 10,
    ]);

    $fieldB = CustomField::factory()->create([
        'model_type' => CustomFieldModel::Customer,
        'name' => 'Field B',
        'sort_order' => 20,
    ]);

    $customerFields = Customer::getCustomFields();

    expect($customerFields->pluck('name')->toArray())->toBe(['Field A', 'Field B', 'Field C']);
});
