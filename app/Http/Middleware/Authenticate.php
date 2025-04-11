<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }
        
        // Get current host and determine if it's a tenant domain
        $currentHost = $request->getHost();
        $centralDomain = Config::get('tenancy.central_domains.0', 'connectcommerce.test');
        $isTenantDomain = $currentHost !== $centralDomain && str_contains($currentHost, '.connectcommerce.test');
        
        // For tenant domains, redirect to tenant login
        if ($isTenantDomain) {
            return 'login';
        }
        
        // For central domain, use standard login route
        return 'login';
    }
} 