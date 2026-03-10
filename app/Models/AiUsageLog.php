<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

final class AiUsageLog extends Model
{
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'team_id',
        'user_id',
        'conversation_id',
        'model',
        'input_tokens',
        'output_tokens',
    ];

    public static function monthlyTokensForTeam(int $teamId): int
    {
        return (int) self::query()
            ->where('team_id', $teamId)
            ->where('created_at', '>=', now()->startOfMonth())
            ->selectRaw('COALESCE(SUM(input_tokens + output_tokens), 0) as total')
            ->value('total');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return array<string, string> */
    #[Override]
    protected function casts(): array
    {
        return [
            'input_tokens' => 'integer',
            'output_tokens' => 'integer',
        ];
    }
}
