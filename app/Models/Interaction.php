<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InteractionCategory;
use App\Enums\InteractionChannel;
use App\Enums\InteractionDirection;
use App\Enums\InteractionStatus;
use App\Enums\InteractionType;
use Database\Factories\InteractionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

final class Interaction extends Model
{
    /** @use HasFactory<InteractionFactory> */
    use HasFactory;

    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'customer_contact_id',
        'user_id',
        'email_template_id',
        'type',
        'category',
        'channel',
        'direction',
        'status',
        'subject',
        'description',
        'interaction_date',
        'duration',
        'next_action',
        'next_action_date',
        'email_sent_at',
        'email_recipient',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(CustomerContact::class, 'customer_contact_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function emailTemplate(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['type', 'category', 'status', 'subject', 'customer_id', 'customer_contact_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected function casts(): array
    {
        return [
            'interaction_date' => 'datetime',
            'next_action_date' => 'date',
            'email_sent_at' => 'datetime',
            'duration' => 'integer',
            'type' => InteractionType::class,
            'category' => InteractionCategory::class,
            'channel' => InteractionChannel::class,
            'direction' => InteractionDirection::class,
            'status' => InteractionStatus::class,
        ];
    }
}
