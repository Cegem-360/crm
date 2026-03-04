<?php

declare(strict_types=1);

use App\Enums\BugReportStatus;
use App\Enums\ComplaintSeverity;
use App\Enums\Role;
use App\Livewire\BugReportSubmission;
use App\Models\BugReport;
use App\Models\User;
use App\Notifications\NewBugReportNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function (): void {
    app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->user = User::factory()->create();
    $this->team = setUpFrontendTenant($this->user);
    $this->actingAs($this->user);
});

it('can render the bug report submission form', function (): void {
    Livewire::test(BugReportSubmission::class)
        ->assertSuccessful()
        ->assertSee('Report a Bug')
        ->assertSet('submitted', false);
});

it('can submit a bug report', function (): void {
    Notification::fake();

    Livewire::test(BugReportSubmission::class)
        ->set('title', 'Button not working')
        ->set('description', 'The save button on the customer form does not respond to clicks.')
        ->set('severity', 'high')
        ->set('url', 'https://crm.test/admin/customers/1/edit')
        ->set('browserInfo', 'Mozilla/5.0 Chrome/120')
        ->call('submit')
        ->assertSet('submitted', true)
        ->assertHasNoErrors();

    $bugReport = BugReport::query()->where('title', 'Button not working')->first();
    expect($bugReport)->not->toBeNull()
        ->and($bugReport->description)->toBe('The save button on the customer form does not respond to clicks.')
        ->and($bugReport->severity)->toBe(ComplaintSeverity::High)
        ->and($bugReport->status)->toBe(BugReportStatus::Open)
        ->and($bugReport->source)->toBe('web_form')
        ->and($bugReport->browser_info)->toBe('Mozilla/5.0 Chrome/120')
        ->and($bugReport->url)->toBe('https://crm.test/admin/customers/1/edit')
        ->and($bugReport->user_id)->toBe($this->user->id)
        ->and($bugReport->team_id)->toBe($this->team->id);
});

it('validates required fields', function (): void {
    Livewire::test(BugReportSubmission::class)
        ->set('title', '')
        ->set('description', '')
        ->call('submit')
        ->assertHasErrors(['title', 'description'])
        ->assertSet('submitted', false);
});

it('validates description minimum length', function (): void {
    Livewire::test(BugReportSubmission::class)
        ->set('title', 'Bug Title')
        ->set('description', 'Too short')
        ->set('severity', 'medium')
        ->call('submit')
        ->assertHasErrors(['description']);
});

it('validates severity must be valid value', function (): void {
    Livewire::test(BugReportSubmission::class)
        ->set('title', 'Bug Title')
        ->set('description', 'A sufficiently long description for validation.')
        ->set('severity', 'invalid_value')
        ->call('submit')
        ->assertHasErrors(['severity']);
});

it('notifies admins and managers when bug report is submitted', function (): void {
    Notification::fake();

    SpatieRole::create(['name' => Role::Admin->value]);
    SpatieRole::create(['name' => Role::Manager->value]);
    SpatieRole::create(['name' => Role::SalesRepresentative->value]);

    $admin = User::factory()->create();
    $admin->assignRole(Role::Admin);
    $admin->teams()->attach($this->team);

    $manager = User::factory()->create();
    $manager->assignRole(Role::Manager);
    $manager->teams()->attach($this->team);

    $salesRep = User::factory()->create();
    $salesRep->assignRole(Role::SalesRepresentative);
    $salesRep->teams()->attach($this->team);

    Livewire::test(BugReportSubmission::class)
        ->set('title', 'Critical Bug')
        ->set('description', 'The entire dashboard is broken and showing errors.')
        ->set('severity', 'critical')
        ->call('submit');

    Notification::assertSentTo([$admin, $manager], NewBugReportNotification::class);
    Notification::assertNotSentTo($salesRep, NewBugReportNotification::class);
});

it('resets form fields after successful submission', function (): void {
    Notification::fake();

    Livewire::test(BugReportSubmission::class)
        ->set('title', 'Bug Title')
        ->set('description', 'A description that is long enough to pass validation.')
        ->set('severity', 'low')
        ->set('url', 'https://crm.test/page')
        ->call('submit')
        ->assertSet('title', '')
        ->assertSet('description', '')
        ->assertSet('severity', 'medium')
        ->assertSet('url', '');
});

it('shows success message after submission', function (): void {
    Notification::fake();

    Livewire::test(BugReportSubmission::class)
        ->set('title', 'Bug Title')
        ->set('description', 'A description that is long enough to pass validation.')
        ->set('severity', 'medium')
        ->call('submit')
        ->assertSet('submitted', true)
        ->assertSee('Bug Report Submitted Successfully');
});

it('is accessible via the bug reports submit route', function (): void {
    $response = $this->get(route('dashboard.bug-reports.submit', ['team' => $this->team]));

    $response->assertSuccessful();
    $response->assertSeeLivewire(BugReportSubmission::class);
});
