<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display the specified category with its products.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        // Get all child category IDs to include their products too
        $categoryIds = [$category->id];
        
        $childCategories = Category::where('parent_id', $category->id)->get();
        foreach ($childCategories as $child) {
            $categoryIds[] = $child->id;
        }
        
        // Get products for this category and its children
        $products = Product::whereHas('categories', function($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->where('status', 'published')
            ->paginate(12);
            
        // Get all categories for the sidebar
        $categories = Category::where('parent_id', null)->with('children')->get();
        
        return view('storefront.categories.show', compact('category', 'products', 'categories'));
    }
} 