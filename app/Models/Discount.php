<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Discount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'min_order_amount',
        'starts_at',
        'ends_at',
        'usage_limit',
        'individual_use_only',
        'exclude_sale_items',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'individual_use_only' => 'boolean',
        'exclude_sale_items' => 'boolean',
    ];

    /**
     * Get the products associated with the discount.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'discount_product');
    }

    /**
     * Determine if the discount is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();

        if ($this->starts_at && $this->starts_at->gt($now)) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->lt($now)) {
            return false;
        }

        return true;
    }

    /**
     * Calculate the discount amount for a given price.
     *
     * @param float $price
     * @return float
     */
    public function calculateDiscount(float $price): float
    {
        if ($this->type === 'percentage') {
            return ($price * $this->value) / 100;
        }

        if ($this->type === 'fixed_amount') {
            return min($price, $this->value);
        }

        return 0;
    }
} 