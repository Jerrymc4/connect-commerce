<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class SyncTenantsUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:sync-users {--tenant= : The ID of the tenant to sync users for} {--email= : Sync a specific user by email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy users from central database to tenant databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantId = $this->option('tenant');
        $email = $this->option('email');
        
        // Get the tenants to process
        if ($tenantId) {
            $tenants = Store::where('id', $tenantId)->get();
            if ($tenants->isEmpty()) {
                $this->error("Tenant with ID {$tenantId} not found.");
                return 1;
            }
        } else {
            $tenants = Store::all();
        }
        
        // Get users to sync
        if ($email) {
            $users = User::where('email', $email)->get();
            if ($users->isEmpty()) {
                $this->error("User with email {$email} not found.");
                return 1;
            }
        } else {
            $users = User::all();
        }
        
        $this->info("Starting user synchronization for " . count($users) . " users to " . count($tenants) . " tenants");
        
        foreach ($tenants as $tenant) {
            $this->info("Processing tenant: {$tenant->id} ({$tenant->name})");
            
            // Check for a user linked to this tenant
            $ownerId = $tenant->getUserId();
            if (!$ownerId) {
                $data = $tenant->data;
                $ownerId = $data['user_id'] ?? null;
            }
            
            $ownerUser = null;
            if ($ownerId) {
                $ownerUser = User::find($ownerId);
                if ($ownerUser) {
                    $this->info("Found owner: {$ownerUser->name} <{$ownerUser->email}>");
                }
            }
            
            // Switch to tenant context
            $tenant->run(function () use ($users, $tenant, $ownerUser) {
                // First, ensure the owner is synced if we found one
                if ($ownerUser) {
                    $this->syncUser($ownerUser, $tenant, true);
                }
                
                // Then sync all other users if desired
                foreach ($users as $user) {
                    // Skip the owner as we've already synced them
                    if ($ownerUser && $user->id === $ownerUser->id) {
                        continue;
                    }
                    
                    $this->syncUser($user, $tenant);
                }
            });
            
            $this->info("Tenant {$tenant->id} processed successfully.");
        }
        
        $this->info("User synchronization completed.");
        return 0;
    }
    
    /**
     * Sync a single user to a tenant database
     */
    protected function syncUser($user, $tenant, $isOwner = false)
    {
        $this->info("Processing user: {$user->name} <{$user->email}>");
        
        // Check if user already exists in tenant database
        $existingUser = DB::table('users')->where('email', $user->email)->first();
        
        if ($existingUser) {
            $this->info("User already exists in tenant database. Updating...");
            
            // Update existing user
            DB::table('users')
                ->where('email', $user->email)
                ->update([
                    'name' => $user->name,
                    'password' => $user->password,
                    'updated_at' => now(),
                ]);
        } else {
            $this->info("Creating user in tenant database...");
            
            // Create new user in tenant database
            DB::table('users')->insert([
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'remember_token' => $user->remember_token,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            if ($isOwner) {
                $this->info("User {$user->email} set as owner for tenant {$tenant->id}");
            }
        }
        
        $this->info("User {$user->email} synchronized to tenant database successfully.");
    }
}
