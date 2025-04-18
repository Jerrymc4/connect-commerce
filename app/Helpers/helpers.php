<?php

use Stancl\Tenancy\Tenancy;

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
                // Format: https://{tenant-domain}/storage/{path}
                $protocol = request()->secure() ? 'https://' : 'http://';
                return $protocol . $domain->domain . '/' . ltrim($path, '/');
            }
        }
        
        // Fall back to regular asset if tenancy not initialized
        return asset($path);
    }
} 