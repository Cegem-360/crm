<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ComplaintSeverity;
use App\Enums\ComplaintStatus;
use App\Models\Concerns\BelongsToTeam;
use App\Models\Concerns\HasCustomFields;
use Database\Factories\ComplaintFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

final class Complaint extends Model
{
    use BelongsToTeam;
    use HasCustomFields;

    /** @use HasFactory<ComplaintFactory> */
    use HasFactory;

    use LogsActivity;

    protected $fillable = [
        'team_id',
        'customer_id',
        'order_id',
        'reported_by',
        'assigned_to',
        'title',
        'description',
        'severity',
        'status',
        'resolution',
        'reported_at',
        'resolved_at',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function escalations(): HasMany
    {
        return $this->hasMany(ComplaintEscalation::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'severity', 'assigned_to', 'resolution', 'resolved_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'severity' => ComplaintSeverity::class,
            'status' => ComplaintStatus::class,
            'reported_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }
}
