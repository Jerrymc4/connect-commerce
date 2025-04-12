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
        'provider',
        'payment_type',
        'token_id',
        'card_type',
        'last_four',
        'expiry_month',
        'expiry_year',
        'cardholder_name',
        'is_default',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'token_id',
    ];

    /**
     * Get the customer that owns the payment method.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
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
     * Get the expiry date in MM/YY format.
     */
    public function getExpiryDateAttribute(): string
    {
        if (!$this->expiry_month || !$this->expiry_year) {
            return '';
        }

        return "{$this->expiry_month}/{$this->expiry_year}";
    }

    /**
     * Get the formatted payment method description.
     */
    public function getDescriptionAttribute(): string
    {
        $type = ucfirst($this->card_type ?? $this->payment_type);
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
        return $query->where('payment_type', 'credit_card');
    }
} 