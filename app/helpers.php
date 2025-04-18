<?php

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\HtmlString;
use Stancl\Tenancy\Tenancy;

if (!function_exists('tenant_vite_assets')) {
    /**
     * Generate the URL for Vite hot module replacement for tenant domains
     *
     * @param  array  $assets
     * @return \Illuminate\Support\HtmlString
     */
    function tenant_vite_assets($assets)
    {
        return Vite::useHotFile(public_path('hot'))
            ->useBuildDirectory('build')
            ->withEntryPoints($assets)
            ->toHtml();
    }
}

// Fallback vite function for Laravel 12 support
if (!function_exists('vite')) {
    /**
     * Generate the URL for Vite hot module replacement
     *
     * @param  array|string  $entryPoints
     * @param  string  $buildDirectory
     * @return \Illuminate\Support\HtmlString
     */
    function vite($entryPoints, $buildDirectory = 'build')
    {
        return Vite::useHotFile(public_path('hot'))
            ->useBuildDirectory($buildDirectory)
            ->withEntryPoints($entryPoints)
            ->toHtml();
    }
}

if (!function_exists('tenant_asset')) {
    /**
     * Generate a tenant-specific asset URL.
     *
     * @param string $path
     * @return string
     */
    function tenant_asset($path)
    {
        $tenancy = app(Tenancy::class);
        
        if ($tenancy->initialized && $tenant = $tenancy->tenant) {
            // Get the current tenant's domain
            $domain = $tenant->domains()->first();
            
            if ($domain) {
                // Remove 'storage/' prefix if it exists to prevent duplication
                if (strpos($path, 'storage/') === 0) {
                    $path = substr($path, 8); // Remove the 'storage/' prefix
                }
                
                // Format tenant URL using the correct tenancy/assets path pattern
                $protocol = request()->secure() ? 'https://' : 'http://';
                $domainStr = $domain->domain;
                $url = $protocol . $domainStr . '/tenancy/assets/storage/' . ltrim($path, '/');
                
                // Log the generated URL for debugging
                \Illuminate\Support\Facades\Log::debug('Tenant asset URL generated', [
                    'original_path' => $path,
                    'tenant_id' => $tenant->id,
                    'domain' => $domainStr,
                    'final_url' => $url
                ]);
                
                return $url;
            }
        }
        
        // Fall back to regular asset if tenancy not initialized
        return asset($path);
    }
} 