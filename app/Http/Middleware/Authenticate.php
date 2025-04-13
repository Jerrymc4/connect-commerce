<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // Determine which login route to use based on the requested path
            if (str_starts_with($request->path(), 'admin')) {
                return route('tenant.login');
            }
            
            // For customer-facing pages, redirect to customer login
            return route('customer.login');
        }
        
        return null;
    }
} 