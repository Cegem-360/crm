<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SupportTicketMessageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

final class SupportTicketMessage extends Model
{
    /** @use HasFactory<SupportTicketMessageFactory> */
    use HasFactory;

    protected $fillable = [
        'support_ticket_id',
        'user_id',
        'body',
        'is_internal_note',
        'is_read',
        'read_at',
    ];

    public function supportTicket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'is_internal_note' => 'boolean',
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }
}
