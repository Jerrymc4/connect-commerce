<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Display the storefront homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Log current connection and tenant information for debugging
            Log::info('Current DB connection: ' . DB::connection()->getName());
            Log::info('Current tenant: ', ['tenant' => tenant() ? tenant()->toArray() : 'No tenant']);
            
            // Get categories - use simple approach without is_featured column
            $featuredCategories = Category::whereNull('parent_id')
                ->take(4)
                ->get();
                
            // Log what we found for debugging
            Log::info('Found ' . $featuredCategories->count() . ' categories');
            Log::info('Categories query: ' . Category::whereNull('parent_id')->toSql());
            
            // Check what status values exist in the database
            $statusValues = Product::distinct()->pluck('status')->toArray();
            Log::info('Available product status values: ' . implode(', ', $statusValues));
            
            // Get featured products - try with different status or no status filter if needed
            $productsQuery = Product::query();
            
            // Only filter by status if there are products with this status
            if (in_array('published', $statusValues)) {
                $productsQuery->where('status', 'published');
            } elseif (in_array('active', $statusValues)) {
                $productsQuery->where('status', 'active');
            }
            
            $featuredProducts = $productsQuery->latest()->take(8)->get();
            
            // Log what we found for debugging
            Log::info('Found ' . $featuredProducts->count() . ' featured products');
            Log::info('Featured products query: ' . $productsQuery->toSql());
            
            // If we still have no products, try without status filter as a fallback
            if ($featuredProducts->isEmpty()) {
                Log::info('No products found with status filter, trying without filter');
                $featuredProducts = Product::latest()->take(8)->get();
                Log::info('Found ' . $featuredProducts->count() . ' products without status filter');
            }
            
            // Get new arrivals - use the same approach for status
            $newArrivalsQuery = Product::query();
            if (in_array('published', $statusValues)) {
                $newArrivalsQuery->where('status', 'published');
            } elseif (in_array('active', $statusValues)) {
                $newArrivalsQuery->where('status', 'active');
            }
            
            $newArrivals = $newArrivalsQuery->latest()->take(4)->get();
            
            // Get bestsellers with same approach
            $bestsellersQuery = Product::query();
            if (in_array('published', $statusValues)) {
                $bestsellersQuery->where('status', 'published');
            } elseif (in_array('active', $statusValues)) {
                $bestsellersQuery->where('status', 'active');
            }
            
            $bestsellers = $bestsellersQuery->inRandomOrder()->take(4)->get();
            
            $storeName = tenant()->name;
            
            return view('storefront.home', compact(
                'featuredCategories', 
                'featuredProducts', 
                'newArrivals', 
                'bestsellers',
                'storeName'
            ));
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in HomeController@index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Fallback with empty collections
            return view('storefront.home', [
                'featuredCategories' => collect(),
                'featuredProducts' => collect(),
                'newArrivals' => collect(),
                'bestsellers' => collect(),
                'storeName' => tenant()->name,
                'error' => 'There was an error loading the products and categories.'
            ]);
        }
    }
} 