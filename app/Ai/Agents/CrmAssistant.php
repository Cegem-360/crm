<?php

declare(strict_types=1);

namespace App\Ai\Agents;

use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;

#[Provider(Lab::Anthropic)]
#[Temperature(0.7)]
final class CrmAssistant implements Agent, Conversational
{
    use Promptable;
    use RemembersConversations;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): string
    {
        return <<<'INSTRUCTIONS'
        You are a helpful CRM assistant. You help users with questions about their customers, sales, tasks, and general CRM usage.

        Guidelines:
        - Be concise and professional in your responses.
        - When asked about CRM concepts, provide clear explanations.
        - Help users understand best practices for customer relationship management.
        - You can help draft emails, summarize customer interactions, and suggest next steps.
        - Respond in the same language the user writes in.
        - Use markdown formatting when it improves readability.
        INSTRUCTIONS;
    }
}
