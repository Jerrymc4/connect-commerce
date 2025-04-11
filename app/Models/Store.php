<?php
declare(strict_types= 1);

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends BaseTenant implements TenantWithDatabase
{
    use HasFactory, HasDatabase, HasDomains;

    protected $table = "stores";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'tenancy_db_name',
        'data',
        'domain',
        'custom_domain',
        'status',
        'description',
        'logo',
        'owner_name',
        'owner_email',
        'owner_phone',
        'user_id',
        'plan_id',
        'trial_ends_at',
        'features',
        'dns_status',
        'ssl_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'trial_ends_at' => 'datetime',
        'features' => 'array',
    ];

    /**
     * Get the user that owns the store.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products for the store.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the orders for the store.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the customers for the store.
     */
    public function customers(): HasMany
    {
        return $this->hasMany(User::class, 'store_id');
    }

    /**
     * Get the user ID from the data array.
     *
     * @return int|null
     */
    public function getUserId()
    {
        return $this->data['user_id'] ?? null;
    }
}