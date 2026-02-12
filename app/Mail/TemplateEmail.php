<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class TemplateEmail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @param  array<string, string>  $variables
     */
    public function __construct(
        public EmailTemplate $template,
        public array $variables = [],
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->replaceVariables($this->template->subject),
        );
    }

    public function build(): self
    {
        $body = $this->replaceVariables($this->template->body);

        return $this->html($body);
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Replace template variables with actual values.
     */
    private function replaceVariables(string $content): string
    {
        $replacements = [];
        foreach ($this->variables as $key => $value) {
            $replacements['{'.$key.'}'] = $value ?? '';
        }

        return strtr($content, $replacements);
    }
}
