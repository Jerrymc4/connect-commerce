<?php
declare(strict_types= 1);

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use App\Models\Store;

class TenantProvisioner
{
    public static function create(string $name, ?string $userId = null): Store
    {
        // 1. Create a slug + random 5-digit suffix
        $slug = Str::slug($name);
        do {
            $suffix = random_int(10000, 99999);
            $uniqueId = "{$slug}-{$suffix}";
        } while (Store::find($uniqueId));

        // 2. Clean DB name (no dashes)
        $dbName = 'store_' . str_replace('-', '_', $uniqueId);

        // 3. Create the tenant/store record with user_id in the data field
        $store = Store::create([
            'id' => $uniqueId,
            'name' => $name,
            'tenancy_db_name' => $dbName,
            'data' => [
                'user_id' => $userId,
            ],
        ]);

        // 4. Create the domain for the store
        $store->domains()->create([
            'domain' => "$uniqueId.connectcommerce.test",
        ]);
        
        // 5. Run tenant migrations
        $store->run(function () {
            Artisan::call('migrate', [
                '--path' => '/database/migrations/tenant',
                '--force' => true,
            ]);
        });

        return $store;
    }
}
