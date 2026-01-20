<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BugReportStatus;
use App\Enums\ComplaintSeverity;
use Database\Factories\BugReportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

final class BugReport extends Model
{
    /** @use HasFactory<BugReportFactory> */
    use HasFactory;

    use LogsActivity;

    protected $fillable = [
        'customer_id',
        'user_id',
        'title',
        'description',
        'severity',
        'status',
        'source',
        'assigned_to',
        'resolved_at',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'severity', 'customer_id', 'assigned_to'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
            'severity' => ComplaintSeverity::class,
            'status' => BugReportStatus::class,
        ];
    }
}
