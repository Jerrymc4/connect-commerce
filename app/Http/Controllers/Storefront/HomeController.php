<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
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
     * HomeController constructor.
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
            
            // Get featured categories
            $featuredCategories = $this->categoryRepository->getFeaturedCategories(4);
            Log::info('Found ' . $featuredCategories->count() . ' categories');
            
            // Get featured products
            $featuredProducts = $this->productRepository->getFeaturedProducts(8);
            Log::info('Found ' . $featuredProducts->count() . ' featured products');
            
            // Get new arrivals
            $newArrivals = $this->productRepository->getNewArrivals(4);
            
            // Get bestsellers
            $bestsellers = $this->productRepository->getBestSellers(4);
            
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