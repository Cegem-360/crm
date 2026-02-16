<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EmailTemplateCategory;
use App\Enums\InteractionCategory;
use App\Enums\InteractionChannel;
use App\Enums\InteractionDirection;
use App\Enums\InteractionStatus;
use App\Enums\InteractionType;
use App\Mail\TemplateEmail;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\EmailTemplate;
use App\Models\Interaction;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

final class EmailService
{
    /**
     * Send an email using a template and create an Interaction record.
     *
     * @param  array<string, mixed>  $context
     */
    public function send(
        EmailTemplate $template,
        string $recipientEmail,
        string $recipientName,
        array $context = [],
    ): Interaction {
        $variables = $this->buildVariables($context, $recipientName);

        // Send the email
        Mail::to($recipientEmail, $recipientName)
            ->send(new TemplateEmail($template, $variables));

        // Create the Interaction record
        $interaction = $this->createInteraction(
            template: $template,
            recipientEmail: $recipientEmail,
            context: $context,
        );

        // Log the activity
        $this->logActivity($interaction, $template, $recipientEmail);

        return $interaction;
    }

    /**
     * Build variables for template substitution.
     *
     * @param  array<string, mixed>  $context
     * @return array<string, string>
     */
    private function buildVariables(array $context, string $recipientName): array
    {
        $variables = [
            'date' => now()->format('Y-m-d'),
            'contact_name' => $recipientName,
        ];

        $user = Auth::user();
        if ($user) {
            $variables['user_name'] = $user->name;
        }

        if (isset($context['customer']) && $context['customer'] instanceof Customer) {
            $variables['customer_name'] = $context['customer']->name;
        }

        if (isset($context['contact']) && $context['contact'] instanceof CustomerContact) {
            $variables['contact_name'] = $context['contact']->name;
            $variables['contact_email'] = $context['contact']->email ?? '';
        }

        if (isset($context['customer']) && $context['customer'] instanceof Customer) {
            $variables['company_name'] = $context['customer']->name;
            $variables['company_email'] = $context['customer']->email ?? '';
        }

        return $variables;
    }

    /**
     * Create an Interaction record for the sent email.
     *
     * @param  array<string, mixed>  $context
     */
    private function createInteraction(
        EmailTemplate $template,
        string $recipientEmail,
        array $context,
    ): Interaction {

        $user = Auth::user();

        $tenant = Filament::getTenant();
        $teamId = $tenant?->id ?? $context['customer']?->team_id ?? null;

        return Interaction::query()->create([
            'team_id' => $teamId,
            'customer_id' => $context['customer']?->id ?? null,
            'customer_contact_id' => $context['contact']?->id ?? null,
            'user_id' => $user?->id,
            'email_template_id' => $template->id,
            'type' => InteractionType::Email,
            'category' => $this->mapTemplateCategory($template),
            'channel' => InteractionChannel::Email,
            'direction' => InteractionDirection::Outbound,
            'status' => InteractionStatus::Completed,
            'subject' => $template->subject,
            'description' => 'Email sent using template: '.$template->name,
            'interaction_date' => now(),
            'email_sent_at' => now(),
            'email_recipient' => $recipientEmail,
        ]);
    }

    /**
     * Map EmailTemplate category to InteractionCategory.
     */
    private function mapTemplateCategory(EmailTemplate $template): InteractionCategory
    {
        return match ($template->category) {
            EmailTemplateCategory::Sales => InteractionCategory::Sales,
            EmailTemplateCategory::Marketing => InteractionCategory::Marketing,
        };
    }

    /**
     * Log the email sent activity.
     */
    private function logActivity(Interaction $interaction, EmailTemplate $template, string $recipientEmail): void
    {
        activity()
            ->performedOn($interaction)
            ->causedBy(Auth::user())
            ->withProperties([
                'recipient' => $recipientEmail,
                'template_name' => $template->name,
                'subject' => $template->subject,
            ])
            ->event('email_sent')
            ->log(sprintf("Email sent to %s using template '%s'", $recipientEmail, $template->name));
    }
}
