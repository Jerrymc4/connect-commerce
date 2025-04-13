<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @var Category
     */
    protected $model;
    
    /**
     * CategoryRepository constructor.
     *
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getById(int $id): ?Category
    {
        return $this->model->find($id);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBySlug(string $slug): ?Category
    {
        return $this->model->where('slug', $slug)->first();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getParentCategories(): Collection
    {
        return $this->model->whereNull('parent_id')->get();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFeaturedCategories(int $limit = 4): Collection
    {
        // If there's an is_featured column, use it
        if ($this->hasColumn('is_featured')) {
            return $this->model->where('is_featured', true)
                ->take($limit)
                ->get();
        }
        
        // Otherwise, just get top-level categories
        return $this->model->whereNull('parent_id')
            ->take($limit)
            ->get();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getChildCategories(int $parentId): Collection
    {
        return $this->model->where('parent_id', $parentId)->get();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCategoriesWithChildren(): Collection
    {
        return $this->model->where('parent_id', null)->with('children')->get();
    }
    
    /**
     * {@inheritdoc}
     */
    public function create(array $data): Category
    {
        return $this->model->create($data);
    }
    
    /**
     * {@inheritdoc}
     */
    public function update(Category $category, array $data): bool
    {
        return $category->update($data);
    }
    
    /**
     * {@inheritdoc}
     */
    public function delete(Category $category): bool
    {
        return $category->delete();
    }
    
    /**
     * Check if a column exists in the categories table
     * 
     * @param string $column
     * @return bool
     */
    protected function hasColumn(string $column): bool
    {
        try {
            return Schema::hasColumn('categories', $column);
        } catch (\Exception $e) {
            return false;
        }
    }
} 