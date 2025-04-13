<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;
    
    /**
     * ProductController constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }
    
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
            
            // Prepare filters from request
            $filters = [
                'category' => $request->category,
                'sort' => $request->sort,
                'price_min' => $request->price_min,
                'price_max' => $request->price_max
            ];
            
            // Get paginated products with filters
            $products = $this->productRepository->getPaginatedProducts($filters);
            Log::info('Found ' . $products->total() . ' products in ProductController');
            
            // If no products found, try without status filter as fallback
            if ($products->isEmpty()) {
                Log::info('No products found, using fallback query');
                // This fallback is now handled in the repository implementation
            }
            
            // Get all categories for the sidebar
            $categories = $this->categoryRepository->getAll();
            Log::info('Found ' . $categories->count() . ' categories for sidebar');
            
            return view('storefront.products.index', compact('products', 'categories'));
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in ProductController@index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Return an error view with empty collections
            return view('storefront.products.index', [
                'products' => collect(),
                'categories' => $this->categoryRepository->getAll(),
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
            
            // Get product by slug
            $product = $this->productRepository->getBySlug($slug);
            
            if (!$product) {
                throw new \Exception("Product with slug {$slug} not found");
            }
            
            Log::info('Found product with slug ' . $slug . ': ' . $product->name);
            
            // Get related products
            $relatedProducts = $this->productRepository->getRelatedProducts($product);
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