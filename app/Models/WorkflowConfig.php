<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Override;
use App\Models\Concerns\BelongsToTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class WorkflowConfig extends Model
{
    use HasFactory;
    use HasFactory;
    use BelongsToTeam;

    protected $fillable = [
        'team_id',
        'user_id',
        'name',
        'api_token',
        'webhook_url',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
