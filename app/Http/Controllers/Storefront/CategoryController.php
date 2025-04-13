<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    /**
     * Display products from the specified category.
     *
     * @param Request $request
     * @param string $slug
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        // Start building the query for products in this category
        $query = Product::where('category_id', $category->id)
            ->where('status', 'active');
        
        // Apply search filter
        $search = $request->query('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        // Apply price range filters
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        
        // Apply sorting
        $sort = $request->query('sort', 'newest');
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
        
        return view('storefront.categories.show', [
            'category' => $category,
            'products' => $products,
            'categories' => $categories,
            'currentSort' => $sort,
            'search' => $search,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice
        ]);
    }
} 