<?php

namespace App\Providers;

use App\Repositories\Interfaces\AuditLogRepositoryInterface;
use App\Repositories\Eloquent\AuditLogRepository;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use App\Repositories\Interfaces\ThemeRepositoryInterface;
use App\Repositories\Eloquent\ThemeRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the AuditLogRepository
        $this->app->bind(AuditLogRepositoryInterface::class, AuditLogRepository::class);
        
        // Register the AuditLogService
        $this->app->singleton(AuditLogService::class, function ($app) {
            return new AuditLogService(
                $app->make(AuditLogRepositoryInterface::class)
            );
        });
        
        // Bind Theme Repository
        $this->app->bind(ThemeRepositoryInterface::class, ThemeRepository::class);
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
