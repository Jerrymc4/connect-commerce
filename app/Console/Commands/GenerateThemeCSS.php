<?php

namespace App\Console\Commands;

use App\Services\ThemeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateThemeCSS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:generate-css {tenant_id? : The ID of the tenant to generate CSS for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the theme CSS for a tenant';

    /**
     * Execute the console command.
     */
    public function handle(ThemeService $themeService)
    {
        $tenantId = $this->argument('tenant_id');
        
        if ($tenantId) {
            $this->info("Generating theme CSS for tenant {$tenantId}");
            
            try {
                // Set the tenant context
                tenancy()->initialize($tenantId);
                
                // Generate the CSS
                $themeService->generateThemeCSS();
                
                $this->info("Theme CSS generated successfully for tenant {$tenantId}");
            } catch (\Exception $e) {
                $this->error("Failed to generate theme CSS: " . $e->getMessage());
                Log::error("Failed to generate theme CSS", [
                    'tenant_id' => $tenantId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } else {
            $this->info("No tenant ID provided, generating CSS for all tenants");
            
            // This would need to be implemented based on your tenant structure
            $this->error("Generating for all tenants is not implemented yet");
        }
        
        return 0;
    }
} 