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
        $suffix = random_int(10000, 99999);
        $uniqueId = "{$slug}-{$suffix}";

        // 2. Clean DB name (no dashes)
        $dbName = 'store_' . str_replace('-', '_', $uniqueId);

        // 3. Create the database manually
        DB::statement("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // 4. Create the tenant/store record
        $store = Store::create([
            'id' => $uniqueId,
            'name' => $name,
            'user_id' => $userId,
            'database' => $dbName,
            
        ]);
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
