<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'compare_price',
        'status',
        'sku',
        'barcode',
        'quantity',
        'weight',
        'length',
        'width',
        'height',
        'image'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // Generate slug if not provided
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

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
     * This is commented out until the Discount model is created
     */
    // public function discounts(): BelongsToMany
    // {
    //     return $this->belongsToMany(Discount::class, 'discount_product');
    // }
    
    /**
     * Check if the product has a sale price.
     *
     * @return bool
     */
    public function isOnSale(): bool
    {
        return $this->compare_price !== null && $this->compare_price < $this->price;
    }
    
    /**
     * Get the current price of the product (sale price if available, otherwise regular price).
     *
     * @return float
     */
    public function getCurrentPrice(): float
    {
        return $this->isOnSale() ? (float)$this->compare_price : (float)$this->price;
    }
    
    /**
     * Calculate the discount percentage if the product is on sale.
     *
     * @return float|null
     */
    public function getDiscountPercentage(): ?float
    {
        if ($this->isOnSale()) {
            return round((($this->price - $this->compare_price) / $this->price) * 100);
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
        return $this->quantity <= $threshold && $this->quantity > 0;
    }
    
    /**
     * Check if the product is out of stock.
     *
     * @return bool
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    /**
     * Get the categories that belong to the product.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
} 