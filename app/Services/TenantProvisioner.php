<?php
declare(strict_types= 1);

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Store;
use App\Models\User;

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

        // 6. Copy the central user to the tenant database if a userId was provided
        if ($userId) {
            // Get the central user
            $centralUser = User::find($userId);
            
            if ($centralUser) {
                // Copy user to tenant database
                $store->run(function () use ($centralUser) {
                    DB::table('users')->insert([
                        'name' => $centralUser->name,
                        'email' => $centralUser->email,
                        'password' => $centralUser->password, // Already hashed
                        'email_verified_at' => $centralUser->email_verified_at,
                        'remember_token' => $centralUser->remember_token,
                        'created_at' => $centralUser->created_at,
                        'updated_at' => $centralUser->updated_at,
                    ]);
                });
            }
        }

        return $store;
    }
}
