<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Stancl\Tenancy\Resolvers\DomainTenantResolver;

class TenantAssetController extends Controller
{
    protected $tenantResolver;
    
    public function __construct(DomainTenantResolver $tenantResolver)
    {
        $this->tenantResolver = $tenantResolver;
    }
    
    /**
     * Serve assets for tenants with proper CORS headers
     */
    public function serveAsset(Request $request, $path)
    {
        // Get the asset from the build directory
        $assetPath = public_path("build/{$path}");
        
        if (!file_exists($assetPath)) {
            abort(404, 'Asset not found');
        }
        
        $contentType = $this->getContentType($path);
        
        $response = response(file_get_contents($assetPath), 200)
            ->header('Content-Type', $contentType);
        
        // Add CORS headers for cross-domain asset loading
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Methods', 'GET, OPTIONS');
        $response->header('Access-Control-Allow-Headers', 'Content-Type, Accept');
        
        return $response;
    }
    
    /**
     * Get the content type based on file extension
     */
    protected function getContentType($path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        return match($extension) {
            'css' => 'text/css',
            'js' => 'application/javascript',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            default => 'application/octet-stream',
        };
    }
} 