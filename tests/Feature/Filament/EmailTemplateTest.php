<?php

declare(strict_types=1);

use App\Mail\TemplateEmail;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
    $this->team = setUpFilamentTenant($this->user);
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
