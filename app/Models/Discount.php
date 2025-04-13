<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

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
        'usage_count',
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
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'individual_use_only' => 'boolean',
        'exclude_sale_items' => 'boolean',
    ];

    /**
     * Get the products that belong to the discount.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Determine if the discount is active.
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();

        // Check if the discount has expired
        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        // Check if the discount has not started yet
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        // Check if usage limit has been reached
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Increment the usage count.
     */
    public function incrementUsageCount(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Get the discount value formatted.
     */
    public function getFormattedValueAttribute(): string
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        } elseif ($this->type === 'fixed_amount') {
            return '$' . number_format($this->value, 2);
        } else {
            return 'Free Shipping';
        }
    }

    /**
     * Get the formatted start date.
     */
    public function getFormattedStartDateAttribute(): string
    {
        return $this->starts_at ? $this->starts_at->format('M d, Y') : 'N/A';
    }

    /**
     * Get the formatted end date.
     */
    public function getFormattedEndDateAttribute(): string
    {
        return $this->ends_at ? $this->ends_at->format('M d, Y') : 'N/A';
    }

    /**
     * Scope a query to only include active discounts.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include valid discounts.
     */
    public function scopeValid($query)
    {
        $now = now();
        
        return $query->where('status', 'active')
            ->where(function ($query) use ($now) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $now);
            })
            ->where(function ($query) {
                $query->whereNull('usage_limit')
                    ->orWhereRaw('usage_count < usage_limit');
            });
    }
} 