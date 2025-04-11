<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Config;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'find-store',
    ];
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // For tenant domains, ensure we have the proper cookie settings
        if (request()->getHost() !== Config::get('tenancy.central_domains.0', 'connectcommerce.test')) {
            // For POST requests like logout
            if ($request->isMethod('post') && $request->is('logout')) {
                // Check if we need to add the tenant route
                if (!$request->routeIs('tenant.logout')) {
                    // Temporarily add a parameter to indicate we should use tenant routes
                    $request->merge(['_tenant_request' => true]);
                }
            }
        }
        
        return parent::handle($request, $next);
    }
} 