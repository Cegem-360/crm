<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use App\Models\WorkflowConfig;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class SendWorkflowWebhook implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(
        public Product $product,
        public string $event,
        public User $user
    ) {}

    public function handle(): void
    {
        $baseUrl = config('services.workflow.webhook_url');

        // Lekérdezzük a user aktív workflow konfigurációit
        $workflowConfigs = $this->user->workflowConfigs()
            ->where('is_active', true)
            ->get();

        if ($workflowConfigs->isEmpty()) {
            return;
        }

        foreach ($workflowConfigs as $config) {
            $this->sendToWorkflow($config, $baseUrl);
        }
    }

    private function sendToWorkflow(WorkflowConfig $config, ?string $baseUrl): void
    {
        // Ha a workflow-nak van saját URL-je, azt használjuk, különben a globálist
        $url = $config->webhook_url ?: $baseUrl;

        if (empty($url)) {
            return;
        }

        $payload = [
            'event' => 'product.'.$this->event,
            'timestamp' => now()->toIso8601String(),
            'workflow_name' => $config->name,
            'data' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'sku' => $this->product->sku,
                'description' => $this->product->description,
                'category_id' => $this->product->category_id,
                'unit_price' => $this->product->unit_price,
                'tax_rate' => $this->product->tax_rate,
                'is_active' => $this->product->is_active,
            ],
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'X-Webhook-Event' => 'product.'.$this->event,
            'X-User-Token' => $config->api_token,
        ];

        $secret = config('services.workflow.webhook_secret');
        if ($secret) {
            $headers['X-Webhook-Signature'] = hash_hmac('sha256', json_encode($payload), (string) $secret);
        }

        try {
            /** @var Response $response */
            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->post($url, $payload);

            if ($response->failed()) {
                Log::warning('Workflow webhook delivery failed', [
                    'url' => $url,
                    'workflow_id' => $config->id,
                    'workflow_name' => $config->name,
                    'user_id' => $this->user->id,
                    'status' => $response->status(),
                    'event' => 'product.'.$this->event,
                ]);
            }
        } catch (Exception $exception) {
            Log::error('Workflow webhook delivery error', [
                'url' => $url,
                'workflow_id' => $config->id,
                'workflow_name' => $config->name,
                'user_id' => $this->user->id,
                'error' => $exception->getMessage(),
                'event' => 'product.'.$this->event,
            ]);
        }
    }
}
