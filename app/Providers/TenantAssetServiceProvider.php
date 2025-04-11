<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Config;

class TenantAssetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Helper function for tenant vite assets (Using global helper instead)
        // Directive is still useful
        
        // Register a blade directive for tenant vite assets
        Blade::directive('tenantvite', function ($expression) {
            return "<?php echo tenant_vite_assets($expression); ?>";
        });
        
        // Configure cookies for tenants
        if (!app()->runningInConsole()) {
            // Get the current domain
            $host = request()->getHost();
            
            // Check if this is a tenant domain
            $centralDomains = Config::get('tenancy.central_domains', ['connectcommerce.test']);
            $isTenantDomain = !in_array($host, $centralDomains);
            
            if ($isTenantDomain) {
                // Set cookie domain to current host for tenant domains
                Config::set('session.domain', '.' . $host);
                Cookie::setDefaultPathAndDomain('/', '.' . $host);
                
                // Configure cookie for CSRF to work in tenant context
                Config::set('session.same_site', 'lax');
            }
        }
        
        // Set up better console output for asset compilation in tenant context
        $this->app->booted(function () {
            if ($this->app->runningInConsole()) {
                $this->commands([
                    \App\Console\Commands\LinkTenantAssets::class,
                ]);
            }
        });
    }
} 