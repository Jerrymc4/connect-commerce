<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    /**
     * Get all products
     * 
     * @return Collection
     */
    public function getAll(): Collection;
    
    /**
     * Get a product by ID
     * 
     * @param int $id
     * @return Product|null
     */
    public function getById(int $id): ?Product;
    
    /**
     * Get a product by slug
     * 
     * @param string $slug
     * @return Product|null
     */
    public function getBySlug(string $slug): ?Product;
    
    /**
     * Get published products
     * 
     * @param int $limit
     * @return Collection
     */
    public function getPublishedProducts(int $limit = 10): Collection;
    
    /**
     * Get featured products
     * 
     * @param int $limit
     * @return Collection
     */
    public function getFeaturedProducts(int $limit = 8): Collection;
    
    /**
     * Get new arrivals
     * 
     * @param int $limit
     * @return Collection
     */
    public function getNewArrivals(int $limit = 4): Collection;
    
    /**
     * Get best sellers
     * 
     * @param int $limit
     * @return Collection
     */
    public function getBestSellers(int $limit = 4): Collection;
    
    /**
     * Get related products
     * 
     * @param Product $product
     * @param int $limit
     * @return Collection
     */
    public function getRelatedProducts(Product $product, int $limit = 4): Collection;

    /**
     * Get products by category
     * 
     * @param int $categoryId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getProductsByCategory(int $categoryId, int $perPage = 12): LengthAwarePaginator;
    
    /**
     * Get paginated products with filters
     * 
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedProducts(array $filters = [], int $perPage = 12): LengthAwarePaginator;
    
    /**
     * Get available product status values
     * 
     * @return array
     */
    public function getAvailableStatusValues(): array;
    
    /**
     * Create a new product
     * 
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product;
    
    /**
     * Update a product
     * 
     * @param Product $product
     * @param array $data
     * @return bool
     */
    public function update(Product $product, array $data): bool;
    
    /**
     * Delete a product
     * 
     * @param Product $product
     * @return bool
     */
    public function delete(Product $product): bool;
} 