<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'card_type',
        'last_four',
        'holder_name',
        'expiry_month',
        'expiry_year',
        'is_default',
        'token', // For payment processor tokens
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_default' => 'boolean',
        'expiry_month' => 'integer',
        'expiry_year' => 'integer',
    ];

    /**
     * Get the customer that owns the payment method.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get whether the payment method is expired.
     *
     * @return bool
     */
    public function getIsExpiredAttribute()
    {
        $now = now();
        return ($this->expiry_year < $now->year) || 
               ($this->expiry_year == $now->year && $this->expiry_month < $now->month);
    }

    /**
     * Get the expiry date formatted as MM/YY.
     *
     * @return string
     */
    public function getExpiryFormattedAttribute()
    {
        return sprintf('%02d/%s', $this->expiry_month, substr($this->expiry_year, -2));
    }

    /**
     * Get a masked version of the card for display.
     */
    public function getMaskedCardAttribute(): string
    {
        if (!$this->last_four) {
            return '';
        }

        return "•••• •••• •••• {$this->last_four}";
    }

    /**
     * Get the formatted payment method description.
     */
    public function getDescriptionAttribute(): string
    {
        $type = ucfirst($this->card_type);
        return "{$type} ending in {$this->last_four}";
    }

    /**
     * Scope a query to only include default payment methods.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope a query to only include credit cards.
     */
    public function scopeCreditCards($query)
    {
        return $query->where('card_type', 'credit_card');
    }
} 