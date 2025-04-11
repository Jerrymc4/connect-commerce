<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure Vite for multi-tenant domains
        Vite::prefetch(concurrency: 3);
        Vite::useHotFile(public_path('hot'))
            ->useBuildDirectory('build');
        
        // Make Vite handle assets across all tenant subdomains
        Vite::macro('crossOriginAsset', function ($asset) {
            if (app()->environment('local')) {
                return $asset;
            }
            
            return $asset;
        });
        
        // Create a tenant_vite blade directive
        Blade::directive('tenant_vite', function ($expression) {
            return "<?php echo \Illuminate\Support\Facades\Vite::$expression; ?>";
        });
        
        // Force HTTPS in production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
