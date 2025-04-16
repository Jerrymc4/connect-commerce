<?php

namespace App\Console\Commands;

use App\Services\ThemeService;
use Illuminate\Console\Command;
use App\Models\Store;

class GenerateThemeCSS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:generate-css {tenant_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the CSS file for the theme settings';

    /**
     * Execute the console command.
     */
    public function handle(ThemeService $themeService)
    {
        $tenantId = $this->argument('tenant_id');
        
        if ($tenantId) {
            // Set the current tenant
            tenancy()->initialize($tenantId);
            $themeService->generateThemeCSS();
            $this->info("Theme CSS generated for tenant {$tenantId}");
            return 0;
        }
        
        $this->info("No tenant ID provided, generating CSS for all tenants");
        
        // Generate for all tenants
        $stores = Store::all();
        
        if ($stores->isEmpty()) {
            $this->info("No tenants found");
            return 0;
        }
        
        foreach ($stores as $store) {
            $this->info("Generating CSS for tenant {$store->id}");
            tenancy()->initialize($store->id);
            $themeService->generateThemeCSS();
        }
        
        $this->info("All theme CSS files generated successfully");
        
        return 0;
    }
} 