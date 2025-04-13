<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;
    
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    
    /**
     * CategoryController constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }
    
    /**
     * Display the specified category with its products.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        try {
            Log::info('CategoryController@show called for slug: ' . $slug);
            
            // Get category by slug
            $category = $this->categoryRepository->getBySlug($slug);
            
            if (!$category) {
                throw new \Exception("Category with slug {$slug} not found");
            }
            
            // Get all child category IDs to include their products too
            $categoryIds = [$category->id];
            
            $childCategories = $this->categoryRepository->getChildCategories($category->id);
            foreach ($childCategories as $child) {
                $categoryIds[] = $child->id;
            }
            
            // Get products for this category and its children with pagination
            $products = $this->productRepository->getProductsByCategory($category->id, 12);
            
            // Get all categories for the sidebar
            $categories = $this->categoryRepository->getCategoriesWithChildren();
            
            return view('storefront.categories.show', compact('category', 'products', 'categories'));
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in CategoryController@show: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Redirect to products page with an error message
            return redirect()->route('storefront.products.index')
                ->with('error', 'The requested category could not be found.');
        }
    }
} 