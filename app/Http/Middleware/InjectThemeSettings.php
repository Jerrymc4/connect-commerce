<?php

namespace App\Http\Middleware;

use App\Services\ThemeService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class InjectThemeSettings
{
    protected $themeService;
    
    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only inject theme settings for storefront routes
        if ($this->isStorefrontRoute($request)) {
            $themeSettings = $this->themeService->getThemeSettings();
            
            // Share settings with all views
            View::share('themeSettings', $themeSettings);
        }
        
        return $next($request);
    }
    
    /**
     * Determine if the request is for a storefront route.
     *
     * @param Request $request
     * @return bool
     */
    private function isStorefrontRoute(Request $request): bool
    {
        // Match any route in the storefront group but not admin routes
        return $request->is('*') && 
               !$request->is('admin*') && 
               !$request->is('*/admin*');
    }
} 