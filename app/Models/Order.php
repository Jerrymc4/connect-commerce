<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'payment_status',
        'payment_method',
        'payment_id',
        'subtotal',
        'tax',
        'shipping',
        'discount',
        'total',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zipcode',
        'shipping_country',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zipcode',
        'billing_country',
        'notes',
        'tracking_number',
        'shipping_provider',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the user that placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    /**
     * Gets the status text with proper formatting
     * 
     * @return string
     */
    public function getStatusTextAttribute(): string
    {
        return ucfirst($this->status);
    }
    
    /**
     * Gets the payment status text with proper formatting
     * 
     * @return string
     */
    public function getPaymentStatusTextAttribute(): string
    {
        return ucfirst($this->payment_status);
    }
    
    /**
     * Gets CSS classes for the order status badge
     * 
     * @return string
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
    
    /**
     * Gets CSS classes for the payment status badge
     * 
     * @return string
     */
    public function getPaymentStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'failed' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
    
    /**
     * Calculates how long ago the order was placed in a human-readable format
     * 
     * @return string
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
    
    /**
     * Get the formatted order date
     * 
     * @return string
     */
    public function getOrderDateAttribute(): string
    {
        return $this->created_at->format('M d, Y');
    }
    
    /**
     * Check if the order is recent (placed in the last 24 hours)
     * 
     * @return bool
     */
    public function isRecent(): bool
    {
        return $this->created_at->gt(Carbon::now()->subDay());
    }
} 