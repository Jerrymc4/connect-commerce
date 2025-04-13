<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    /**
     * Get all categories
     * 
     * @return Collection
     */
    public function getAll(): Collection;
    
    /**
     * Get a category by ID
     * 
     * @param int $id
     * @return Category|null
     */
    public function getById(int $id): ?Category;
    
    /**
     * Get a category by slug
     * 
     * @param string $slug
     * @return Category|null
     */
    public function getBySlug(string $slug): ?Category;
    
    /**
     * Get parent categories
     * 
     * @return Collection
     */
    public function getParentCategories(): Collection;
    
    /**
     * Get featured categories
     * 
     * @param int $limit
     * @return Collection
     */
    public function getFeaturedCategories(int $limit = 4): Collection;
    
    /**
     * Get child categories
     * 
     * @param int $parentId
     * @return Collection
     */
    public function getChildCategories(int $parentId): Collection;
    
    /**
     * Get all categories with their children
     * 
     * @return Collection
     */
    public function getCategoriesWithChildren(): Collection;
    
    /**
     * Create a new category
     * 
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category;
    
    /**
     * Update a category
     * 
     * @param Category $category
     * @param array $data
     * @return bool
     */
    public function update(Category $category, array $data): bool;
    
    /**
     * Delete a category
     * 
     * @param Category $category
     * @return bool
     */
    public function delete(Category $category): bool;
} 