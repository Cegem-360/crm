<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Scopes\TeamScope;
use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTeam
{
    public static function bootBelongsToTeam(): void
    {
        static::addGlobalScope(new TeamScope);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
