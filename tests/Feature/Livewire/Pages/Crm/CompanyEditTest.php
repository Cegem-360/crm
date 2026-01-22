<?php

declare(strict_types=1);

use App\Livewire\Pages\Crm\Companies\EditCompany;
use App\Models\Company;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->team = setUpFrontendTenant($this->user);
});

it('can render create page', function () {
    $component = Livewire::test(EditCompany::class)
        ->assertSuccessful();

    expect($component->get('company')?->exists)->toBeFalsy();
});

it('can render edit page with existing company', function () {
    $company = Company::factory()->for($this->team)->create([
        'name' => 'Existing Company',
        'email' => 'existing@company.com',
    ]);

    Livewire::test(EditCompany::class, ['company' => $company])
        ->assertSuccessful()
        ->assertFormSet([
            'name' => 'Existing Company',
            'email' => 'existing@company.com',
        ]);
});

it('can create a company', function () {
    Livewire::test(EditCompany::class)
        ->set('team', $this->team)
        ->fillForm([
            'name' => 'Test Company',
            'email' => 'test@company.com',
            'tax_number' => '12345678',
            'registration_number' => 'REG123',
        ])
        ->call('save')
        ->assertRedirect();

    expect(Company::where('name', 'Test Company')->exists())->toBeTrue();
});

it('validates required fields on create', function () {
    Livewire::test(EditCompany::class)
        ->fillForm([
            'name' => '',
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

it('validates email format', function () {
    Livewire::test(EditCompany::class)
        ->fillForm([
            'name' => 'Test Company',
            'email' => 'not-an-email',
        ])
        ->call('save')
        ->assertHasFormErrors(['email']);
});

it('can update an existing company', function () {
    $company = Company::factory()->for($this->team)->create([
        'name' => 'Old Name',
    ]);

    Livewire::test(EditCompany::class, ['company' => $company])
        ->set('team', $this->team)
        ->fillForm([
            'name' => 'New Name',
        ])
        ->call('save')
        ->assertRedirect();

    expect($company->fresh()->name)->toBe('New Name');
});

it('redirects to view page after save', function () {
    $company = Company::factory()->for($this->team)->create();

    Livewire::test(EditCompany::class, ['company' => $company])
        ->set('team', $this->team)
        ->fillForm([
            'name' => 'Updated Company',
        ])
        ->call('save')
        ->assertRedirectContains('/companies/');
});
