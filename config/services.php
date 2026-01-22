<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Workflow Integration
    |--------------------------------------------------------------------------
    */
    'secondary_app' => [
        'url' => env('SECONDARY_APP_URL'),
        'api_key' => env('SECONDARY_APP_API_KEY'),
    ],

    'subscriber_api_key' => env('SUBSCRIBER_API_KEY'),

    'workflow' => [
        'webhook_url' => env('WORKFLOW_WEBHOOK_URL'),
        'webhook_secret' => env('WORKFLOW_WEBHOOK_SECRET'),
    ],
];
