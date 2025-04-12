<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'city',
        'state',
        'zipcode',
        'country',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Get the orders for this user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    
    /**
     * Check if user is a customer.
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
    
    /**
     * Check if user is a store owner.
     */
    public function isStoreOwner(): bool
    {
        return $this->role === 'owner';
    }
    
    /**
     * Get the user's full address as a formatted string.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [];
        
        if ($this->address) $parts[] = $this->address;
        
        $cityState = '';
        if ($this->city) $cityState .= $this->city;
        if ($this->city && $this->state) $cityState .= ', ';
        if ($this->state) $cityState .= $this->state;
        if ($cityState && $this->zipcode) $cityState .= ' ';
        if ($this->zipcode) $cityState .= $this->zipcode;
        
        if ($cityState) $parts[] = $cityState;
        if ($this->country) $parts[] = $this->country;
        
        return implode(', ', $parts);
    }
}
