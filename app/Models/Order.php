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
        'customer_id',
        'order_number',
        'status',
        'payment_status',
        'payment_method',
        'shipping_method',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total',
        'notes',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
        'shipping_country',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zip',
        'billing_country',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the customer that owns the order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the history records for the order.
     */
    public function history()
    {
        return $this->hasMany(OrderHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Generate a unique order number.
     *
     * @return string
     */
    public static function generateOrderNumber()
    {
        $prefix = date('Ymd');
        $lastOrder = self::where('order_number', 'like', $prefix . '%')
            ->orderBy('order_number', 'desc')
            ->first();
            
        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, strlen($prefix)));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
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