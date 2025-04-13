<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            Log::info('ProductController@index called with request: ' . json_encode($request->all()));
            
            // Check what status values exist in the database
            $statusValues = Product::distinct()->pluck('status')->toArray();
            Log::info('Available product status values in ProductController: ' . implode(', ', $statusValues));
            
            // Start building the query
            $query = Product::query();
            
            // Apply status filter using same approach as HomeController
            if (in_array('published', $statusValues)) {
                $query->where('status', 'published');
            } elseif (in_array('active', $statusValues)) {
                $query->where('status', 'active');
            }
            
            // Apply category filter if provided
            if ($request->has('category') && $request->category) {
                Log::info('Filtering by category ID: ' . $request->category);
                $query->whereHas('categories', function($q) use ($request) {
                    $q->where('categories.id', $request->category);
                });
            }
            
            // Apply sorting if provided
            if ($request->has('sort')) {
                switch ($request->sort) {
                    case 'price_asc':
                        $query->orderBy('price', 'asc');
                        break;
                    case 'price_desc':
                        $query->orderBy('price', 'desc');
                        break;
                    case 'newest':
                        $query->latest();
                        break;
                    default:
                        $query->latest();
                        break;
                }
            } else {
                $query->latest(); // Default sorting
            }
            
            Log::info('Products query SQL: ' . $query->toSql());
            
            // Execute the query with pagination
            $products = $query->paginate(12);
            Log::info('Found ' . $products->total() . ' products in ProductController');
            
            // If no products found with status filter, try without it
            if ($products->isEmpty() && (in_array('published', $statusValues) || in_array('active', $statusValues))) {
                Log::info('No products found with status filter, trying without filter');
                $query = Product::query();
                
                // Re-apply category filter if provided
                if ($request->has('category') && $request->category) {
                    $query->whereHas('categories', function($q) use ($request) {
                        $q->where('categories.id', $request->category);
                    });
                }
                
                // Re-apply sorting
                if ($request->has('sort')) {
                    switch ($request->sort) {
                        case 'price_asc':
                            $query->orderBy('price', 'asc');
                            break;
                        case 'price_desc':
                            $query->orderBy('price', 'desc');
                            break;
                        case 'newest':
                            $query->latest();
                            break;
                        default:
                            $query->latest();
                            break;
                    }
                } else {
                    $query->latest();
                }
                
                Log::info('Products query without status filter: ' . $query->toSql());
                $products = $query->paginate(12);
                Log::info('Found ' . $products->total() . ' products without status filter');
            }
            
            // Get all categories for the sidebar
            $categories = Category::all();
            Log::info('Found ' . $categories->count() . ' categories for sidebar');
            
            return view('storefront.products.index', compact('products', 'categories'));
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in ProductController@index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Return an error view with empty collections
            return view('storefront.products.index', [
                'products' => collect(),
                'categories' => Category::all(),
                'error' => 'There was an error loading the products. Please try again later.'
            ]);
        }
    }
    
    /**
     * Display the specified product.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        try {
            Log::info('ProductController@show called for slug: ' . $slug);
            
            // Check what status values exist in the database
            $statusValues = Product::distinct()->pluck('status')->toArray();
            
            $query = Product::where('slug', $slug);
            
            // Apply status filter using same approach as home page
            if (in_array('published', $statusValues)) {
                $query->where('status', 'published');
            } elseif (in_array('active', $statusValues)) {
                $query->where('status', 'active');
            }
            
            $product = $query->firstOrFail();
            Log::info('Found product with slug ' . $slug . ': ' . $product->name);
            
            // Get related products from the same categories
            $relatedProductsQuery = Product::where('id', '!=', $product->id);
            
            // Apply the same status filter to related products
            if (in_array('published', $statusValues)) {
                $relatedProductsQuery->where('status', 'published');
            } elseif (in_array('active', $statusValues)) {
                $relatedProductsQuery->where('status', 'active');
            }
            
            // Filter by same categories if possible
            if ($product->categories->count() > 0) {
                $categoryIds = $product->categories->pluck('id')->toArray();
                $relatedProductsQuery->whereHas('categories', function($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
            
            $relatedProducts = $relatedProductsQuery->take(4)->get();
            Log::info('Found ' . $relatedProducts->count() . ' related products');
            
            return view('storefront.products.show', compact('product', 'relatedProducts'));
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in ProductController@show: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Redirect to products page with an error message
            return redirect()->route('storefront.products.index')
                ->with('error', 'The requested product could not be found.');
        }
    }
} 