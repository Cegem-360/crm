<?php

declare(strict_types=1);

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Filament\Resources\Tasks\Pages\CreateTask;
use App\Filament\Resources\Tasks\Pages\EditTask;
use App\Filament\Resources\Tasks\Pages\ListTasks;
use App\Filament\Resources\Tasks\Pages\ViewTask;
use App\Models\Customer;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Permission;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();

    Permission::query()->firstOrCreate(['name' => 'view_any_task']);
    Permission::query()->firstOrCreate(['name' => 'view_task']);
    Permission::query()->firstOrCreate(['name' => 'create_task']);
    Permission::query()->firstOrCreate(['name' => 'update_task']);
    Permission::query()->firstOrCreate(['name' => 'delete_task']);

    $this->user->givePermissionTo([
        'view_any_task',
        'view_task',
        'create_task',
        'update_task',
        'delete_task',
    ]);

    $this->actingAs($this->user);
    $this->team = setUpFilamentTenant($this->user);
});

it('can render task list page', function (): void {
    livewire(ListTasks::class)
        ->assertSuccessful();
});

it('can list tasks', function (): void {
    $tasks = Task::factory()
        ->count(3)
        ->for($this->team)
        ->create();

    livewire(ListTasks::class)
        ->assertCanSeeTableRecords($tasks);
});

it('can render create task page', function (): void {
    livewire(CreateTask::class)
        ->assertSuccessful();
});

it('can create a task', function (): void {
    Notification::fake();

    $assignee = User::factory()->create();
    $assignee->teams()->attach($this->team);
    $customer = Customer::factory()->for($this->team)->create();

    livewire(CreateTask::class)
        ->fillForm([
            'customer_id' => $customer->id,
            'assigned_to' => $assignee->id,
            'assigned_by' => $this->user->id,
            'title' => 'Follow up with client',
            'description' => 'Call the client about the proposal',
            'priority' => TaskPriority::High->value,
            'status' => TaskStatus::Pending->value,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Task::class, [
        'team_id' => $this->team->id,
        'customer_id' => $customer->id,
        'assigned_to' => $assignee->id,
        'assigned_by' => $this->user->id,
        'title' => 'Follow up with client',
    ]);

    Notification::assertSentTo($assignee, TaskAssignedNotification::class);
});

it('can render view task page', function (): void {
    $task = Task::factory()->for($this->team)->create();

    livewire(ViewTask::class, ['record' => $task->id])
        ->assertSuccessful();
});

it('can render edit task page', function (): void {
    $task = Task::factory()->for($this->team)->create();

    livewire(EditTask::class, ['record' => $task->id])
        ->assertSuccessful();
});

it('can update a task', function (): void {
    Notification::fake();

    $customer = Customer::factory()->for($this->team)->create();

    $task = Task::factory()->for($this->team)->pending()->create([
        'customer_id' => $customer->id,
        'assigned_to' => $this->user->id,
        'assigned_by' => $this->user->id,
        'title' => 'Original title',
    ]);

    livewire(EditTask::class, ['record' => $task->id])
        ->assertSchemaStateSet([
            'title' => 'Original title',
        ])
        ->fillForm([
            'title' => 'Updated task title',
            'status' => TaskStatus::InProgress->value,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $task->refresh();

    expect($task)
        ->title->toBe('Updated task title')
        ->status->toBe(TaskStatus::InProgress);
});

it('sends notification when task is reassigned', function (): void {
    Notification::fake();

    $originalAssignee = User::factory()->create();
    $originalAssignee->teams()->attach($this->team);

    $newAssignee = User::factory()->create();
    $newAssignee->teams()->attach($this->team);

    $customer = Customer::factory()->for($this->team)->create();

    $task = Task::factory()->for($this->team)->create([
        'customer_id' => $customer->id,
        'assigned_to' => $originalAssignee->id,
        'assigned_by' => $this->user->id,
    ]);

    // Reset notification fake after factory creation (which triggers observer)
    Notification::fake();

    livewire(EditTask::class, ['record' => $task->id])
        ->fillForm([
            'assigned_to' => $newAssignee->id,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    Notification::assertSentTo($newAssignee, TaskAssignedNotification::class);
    Notification::assertNotSentTo($originalAssignee, TaskAssignedNotification::class);
});

it('validates required fields on create', function (): void {
    livewire(CreateTask::class)
        ->fillForm([
            'assigned_to' => null,
            'assigned_by' => null,
            'title' => null,
            'priority' => null,
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'assigned_to' => 'required',
            'assigned_by' => 'required',
            'title' => 'required',
            'priority' => 'required',
            'status' => 'required',
        ]);
});

it('cannot access list page without permission', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    livewire(ListTasks::class)
        ->assertForbidden();
});

it('cannot access create page without permission', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    livewire(CreateTask::class)
        ->assertForbidden();
});

it('cannot access edit page without permission', function (): void {
    $task = Task::factory()->for($this->team)->create();
    $user = User::factory()->create();
    $user->teams()->syncWithoutDetaching($this->team);
    $this->actingAs($user);

    livewire(EditTask::class, ['record' => $task->id])
        ->assertForbidden();
});

it('displays assigned user names in view page', function (): void {
    $assignee = User::factory()->create(['name' => 'John Doe']);
    $assigner = User::factory()->create(['name' => 'Jane Smith']);
    $customer = Customer::factory()->for($this->team)->create();

    $task = Task::factory()->for($this->team)->create([
        'customer_id' => $customer->id,
        'assigned_to' => $assignee->id,
        'assigned_by' => $assigner->id,
    ]);

    livewire(ViewTask::class, ['record' => $task->id])
        ->assertSuccessful()
        ->assertSchemaComponentExists('assignedUser.name', 'infolist')
        ->assertSchemaComponentExists('assigner.name', 'infolist');
});
