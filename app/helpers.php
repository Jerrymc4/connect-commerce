<?php

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\HtmlString;

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