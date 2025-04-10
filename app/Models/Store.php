<?php
declare(strict_types= 1);

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Store extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    protected $table = "stores";

    protected $fillable = [
        'id',
        'name',
        'tenancy_db_name',
        'data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

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