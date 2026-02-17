<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\TeamThroughScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

final class QuoteItem extends Model
{
    use HasFactory;

    /** @var array<string, mixed> */
    protected $attributes = [
        'quantity' => 1,
        'unit_price' => 0,
        'discount_percent' => 0,
        'discount_amount' => 0,
        'tax_rate' => 0,
        'total' => 0,
    ];

    protected $fillable = [
        'quote_id',
        'product_id',
        'description',
        'quantity',
        'unit_price',
        'discount_percent',
        'discount_amount',
        'tax_rate',
        'total',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function updateTotal(): void
    {
        $subtotal = $this->quantity * $this->unit_price;
        $discountedSubtotal = $subtotal * (1 - (float) $this->discount_percent / 100);
        $taxAmount = $subtotal * $this->tax_rate / 100;

        $this->total = $discountedSubtotal + $taxAmount;
        $this->save();
    }

    protected static function booted(): void
    {
        self::addGlobalScope(new TeamThroughScope('quote'));
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'discount_percent' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }
}
