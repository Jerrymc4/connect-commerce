<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // Get filters from request
        $category = $request->query('category');
        $sort = $request->query('sort', 'newest');
        $search = $request->query('search');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        
        // Start building the query
        $query = Product::where('status', 'active');
        
        // Apply category filter
        if ($category) {
            $query->where('category_id', $category);
        }
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        // Apply price range filters
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        
        // Apply sorting
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Get paginated results
        $products = $query->paginate(12)->withQueryString();
        
        // Get all categories for filter sidebar
        $categories = Category::orderBy('name')->get();
        
        return view('storefront.products.index', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $category,
            'currentSort' => $sort,
            'search' => $search,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice
        ]);
    }
    
    /**
     * Display the specified product.
     *
     * @param string $slug
     * @return \Illuminate\Contracts\View\View
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();
            
        // Get related products
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('status', 'active')
            ->inRandomOrder()
            ->limit(4)
            ->get();
            
        return view('storefront.products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
} 