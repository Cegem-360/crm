<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTeam;
use Database\Factories\GoogleCalendarTokenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

final class GoogleCalendarToken extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<GoogleCalendarTokenFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'team_id',
        'access_token',
        'refresh_token',
        'expires_at',
        'calendar_id',
        'sync_enabled',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        if ($this->expires_at === null) {
            return true;
        }

        return $this->expires_at->isPast();
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
            'refresh_token' => 'encrypted',
            'expires_at' => 'datetime',
            'sync_enabled' => 'boolean',
        ];
    }
}
