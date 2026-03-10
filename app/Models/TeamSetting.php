<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Currency;
use App\Models\Concerns\BelongsToTeam;
use Database\Factories\TeamSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

final class TeamSetting extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<TeamSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'team_id',
        'currency',
        'ai_monthly_token_limit',
        'gemini_api_key',
    ];

    public static function currentCurrency(): string
    {
        $team = app()->bound(Team::CONTAINER_BINDING)
            ? resolve(Team::CONTAINER_BINDING)
            : null;

        if (! $team instanceof Team) {
            return Currency::HUF->value;
        }

        $setting = self::query()
            ->withoutGlobalScopes()
            ->where('team_id', $team->getKey())
            ->first();

        return $setting?->currency->value ?? Currency::HUF->value;
    }

    /** @return array<string, string> */
    #[Override]
    protected function casts(): array
    {
        return [
            'currency' => Currency::class,
            'ai_monthly_token_limit' => 'integer',
            'gemini_api_key' => 'encrypted',
        ];
    }
}
