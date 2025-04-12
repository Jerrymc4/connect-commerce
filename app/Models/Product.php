<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'sku',
        'slug',
        'description',
        'price',
        'sale_price',
        'category_id',
        'status',
        'stock',
        'weight',
        'dimensions',
        'image',
        'store_id',
        'track_inventory',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'stock' => 'integer',
        'track_inventory' => 'boolean',
    ];

    /**
     * Get the store that owns the product.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the product status as a badge.
     *
     * @return string
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'draft' => 'bg-gray-100 text-gray-800',
            'out_of_stock' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the discounts associated with the product.
     */
    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'discount_product');
    }
    
    /**
     * Check if the product has a sale price.
     *
     * @return bool
     */
    public function isOnSale(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }
    
    /**
     * Get the current price of the product (sale price if available, otherwise regular price).
     *
     * @return float
     */
    public function getCurrentPrice(): float
    {
        return $this->isOnSale() ? (float)$this->sale_price : (float)$this->price;
    }
    
    /**
     * Calculate the discount percentage if the product is on sale.
     *
     * @return float|null
     */
    public function getDiscountPercentage(): ?float
    {
        if ($this->isOnSale()) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        
        return null;
    }
    
    /**
     * Check if the product is low on stock.
     *
     * @param int $threshold
     * @return bool
     */
    public function isLowStock(int $threshold = 5): bool
    {
        return $this->track_inventory && $this->stock <= $threshold && $this->stock > 0;
    }
    
    /**
     * Check if the product is out of stock.
     *
     * @return bool
     */
    public function isOutOfStock(): bool
    {
        return $this->track_inventory && $this->stock <= 0;
    }
} 