<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\User;
use App\Models\WorkflowConfig;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class SendWorkflowWebhook implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(
        public Model $model,
        public string $event,
        public User $user
    ) {}

    public function handle(): void
    {
        $baseUrl = config('services.workflow.webhook_url');

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
        $url = $config->webhook_url ?: $baseUrl;

        if (empty($url)) {
            return;
        }

        $modelType = Str::snake(class_basename($this->model));
        $eventName = $modelType.'.'.$this->event;

        $payload = [
            'event' => $eventName,
            'timestamp' => now()->toIso8601String(),
            'workflow_name' => $config->name,
            'model_type' => $modelType,
            'data' => $this->model->toArray(),
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'X-Webhook-Event' => $eventName,
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
                    'event' => $eventName,
                ]);
            }
        } catch (Exception $exception) {
            Log::error('Workflow webhook delivery error', [
                'url' => $url,
                'workflow_id' => $config->id,
                'workflow_name' => $config->name,
                'user_id' => $this->user->id,
                'error' => $exception->getMessage(),
                'event' => $eventName,
            ]);
        }
    }
}
