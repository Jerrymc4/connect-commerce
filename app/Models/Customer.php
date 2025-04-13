<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'first_name',
        'last_name',
        'email_verified_at',
        'status',
        'accepts_marketing',
        'is_guest',
        'customer_group',
        'tax_exempt',
        'last_login_at',
        'preferences',
        'currency',
        'locale',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'accepts_marketing' => 'boolean',
        'is_guest' => 'boolean',
        'preferences' => 'array',
        'deleted_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the JWT identifier.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array of claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'tenant_id' => tenant()->getId(),
        ];
    }

    /**
     * Get the customer's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Hash the customer's password.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value ? Hash::make($value) : null;
    }

    /**
     * Get the addresses for the customer.
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Get the default billing address for the customer.
     */
    public function defaultBillingAddress()
    {
        return $this->addresses()
            ->where('type', 'billing')
            ->where('is_default', true)
            ->first();
    }

    /**
     * Get the default shipping address for the customer.
     */
    public function defaultShippingAddress()
    {
        return $this->addresses()
            ->where('type', 'shipping')
            ->where('is_default', true)
            ->first();
    }

    /**
     * Get the default address for the customer (any type).
     */
    public function defaultAddress()
    {
        // First try to get default shipping address
        $address = $this->defaultShippingAddress();
        
        // If no default shipping address, try default billing address
        if (!$address) {
            $address = $this->defaultBillingAddress();
        }
        
        // If no default address at all, get the first address
        if (!$address) {
            $address = $this->addresses()->first();
        }
        
        return $address;
    }

    /**
     * Get the payment methods for the customer.
     */
    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    /**
     * Get the default payment method for the customer.
     */
    public function defaultPaymentMethod()
    {
        return $this->paymentMethods()
            ->where('is_default', true)
            ->first();
    }

    /**
     * Get the orders for the customer.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope a query to only include active customers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include customers who accept marketing.
     */
    public function scopeMarketing($query)
    {
        return $query->where('accepts_marketing', true);
    }

    /**
     * Scope a query to only include guest customers.
     */
    public function scopeGuests($query)
    {
        return $query->where('is_guest', true);
    }

    /**
     * Scope a query to only include registered customers.
     */
    public function scopeRegistered($query)
    {
        return $query->where('is_guest', false);
    }

    /**
     * Check if the customer has verified their email.
     */
    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Mark the customer's email as verified.
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }
}
