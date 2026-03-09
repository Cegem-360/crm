<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SupportTicketCategory;
use App\Enums\SupportTicketPriority;
use App\Enums\SupportTicketStatus;
use App\Models\Concerns\BelongsToTeam;
use Database\Factories\SupportTicketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

final class SupportTicket extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<SupportTicketFactory> */
    use HasFactory;

    use LogsActivity;

    protected $fillable = [
        'team_id',
        'user_id',
        'assigned_to',
        'ticket_number',
        'subject',
        'description',
        'category',
        'priority',
        'status',
        'resolved_at',
        'closed_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportTicketMessage::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['subject', 'status', 'priority', 'assigned_to', 'category'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected static function booted(): void
    {
        self::creating(function (SupportTicket $ticket): void {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = 'TKT-'.mb_str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
            }
        });
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'category' => SupportTicketCategory::class,
            'priority' => SupportTicketPriority::class,
            'status' => SupportTicketStatus::class,
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }
}
