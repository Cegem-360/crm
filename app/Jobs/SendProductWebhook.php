<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Http\Client\Response;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class SendProductWebhook implements ShouldQueue
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
        if (empty($this->user->webhook_url)) {
            return;
        }

        $this->sendWebhook($this->user);
    }

    private function sendWebhook(User $user): void
    {
        $payload = [
            'event' => 'product.' . $this->event,
            'timestamp' => now()->toIso8601String(),
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
            'X-Webhook-Event' => 'product.' . $this->event,
        ];

        if ($user->webhook_secret) {
            $signature = hash_hmac('sha256', json_encode($payload), $user->webhook_secret);
            $headers['X-Webhook-Signature'] = $signature;
        }

        try {
            /** @var Response $response */
            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->post($user->webhook_url, $payload);

            if ($response->failed()) {
                Log::warning('Webhook delivery failed', [
                    'user_id' => $user->id,
                    'url' => $user->webhook_url,
                    'status' => $response->status(),
                    'event' => 'product.' . $this->event,
                ]);
            }
        } catch (Exception $exception) {
            Log::error('Webhook delivery error', [
                'user_id' => $user->id,
                'url' => $user->webhook_url,
                'error' => $exception->getMessage(),
                'event' => 'product.' . $this->event,
            ]);
        }
    }
}
