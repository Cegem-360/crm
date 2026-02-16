<?php

declare(strict_types=1);

use App\Enums\EmailTemplateCategory;
use App\Filament\Resources\EmailTemplates\Pages\CreateEmailTemplate;
use App\Filament\Resources\EmailTemplates\Pages\EditEmailTemplate;
use App\Filament\Resources\EmailTemplates\Pages\ListEmailTemplates;
use App\Mail\TemplateEmail;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();

    Permission::query()->firstOrCreate(['name' => 'view_any_email::template']);
    Permission::query()->firstOrCreate(['name' => 'view_email::template']);
    Permission::query()->firstOrCreate(['name' => 'create_email::template']);
    Permission::query()->firstOrCreate(['name' => 'update_email::template']);
    Permission::query()->firstOrCreate(['name' => 'delete_email::template']);

    $this->user->givePermissionTo([
        'view_any_email::template',
        'view_email::template',
        'create_email::template',
        'update_email::template',
        'delete_email::template',
    ]);

    $this->actingAs($this->user);
    $this->team = setUpFilamentTenant($this->user);
});

it('can render email template list page', function (): void {
    livewire(ListEmailTemplates::class)
        ->assertSuccessful();
});

it('can render create email template page', function (): void {
    livewire(CreateEmailTemplate::class)
        ->assertSuccessful();
});

it('can create an email template', function (): void {
    livewire(CreateEmailTemplate::class)
        ->fillForm([
            'name' => 'Welcome Email',
            'subject' => 'Welcome to {customer_name}',
            'body' => '<p>Hello {contact_name}, welcome!</p>',
            'category' => EmailTemplateCategory::Sales->value,
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(EmailTemplate::query()->where('name', 'Welcome Email')->exists())->toBeTrue();
});

it('validates required fields', function (): void {
    livewire(CreateEmailTemplate::class)
        ->fillForm([
            'name' => '',
            'subject' => '',
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'subject' => 'required']);
});

it('can edit an email template', function (): void {
    $template = EmailTemplate::factory()->forTeam($this->team)->create([
        'name' => 'Old Template',
        'created_by' => $this->user->id,
    ]);

    livewire(EditEmailTemplate::class, ['record' => $template->getRouteKey()])
        ->assertFormSet([
            'name' => 'Old Template',
        ])
        ->fillForm([
            'name' => 'Updated Template',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($template->refresh()->name)->toBe('Updated Template');
});

it('can list email templates in table', function (): void {
    $templates = EmailTemplate::factory()->count(3)->forTeam($this->team)->create([
        'created_by' => $this->user->id,
    ]);

    livewire(ListEmailTemplates::class)
        ->assertCanSeeTableRecords($templates);
});

it('replaces variables in `TemplateEmail` mailable', function (): void {
    Mail::fake();

    $template = EmailTemplate::factory()->forTeam($this->team)->create([
        'subject' => 'Hello {customer_name}',
        'body' => '<p>Dear {contact_name}, from {user_name}</p>',
        'created_by' => $this->user->id,
    ]);

    $mailable = new TemplateEmail($template, [
        'customer_name' => 'Acme Corp',
        'contact_name' => 'John',
        'user_name' => 'Admin',
    ]);

    $envelope = $mailable->envelope();

    expect($envelope->subject)->toBe('Hello Acme Corp');

    $built = $mailable->build();
    expect((string) $built->render())->toContain('Dear John, from Admin');
});
