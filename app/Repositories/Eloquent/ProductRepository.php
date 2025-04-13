<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var Product
     */
    protected $model;
    
    /**
     * ProductRepository constructor.
     *
     * @param Product $model
     */
    public function __construct(Product $model)
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
    public function getById(int $id): ?Product
    {
        return $this->model->find($id);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBySlug(string $slug): ?Product
    {
        $query = $this->model->where('slug', $slug);
        
        // Apply status filter if applicable
        $this->applyStatusFilter($query);
        
        return $query->first();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getPublishedProducts(int $limit = 10): Collection
    {
        $query = $this->model->query();
        
        // Apply status filter
        $this->applyStatusFilter($query);
        
        return $query->take($limit)->get();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFeaturedProducts(int $limit = 8): Collection
    {
        $query = $this->model->query();
        
        // Apply status filter
        $this->applyStatusFilter($query);
        
        // Order by latest
        $query->latest();
        
        return $query->take($limit)->get();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getNewArrivals(int $limit = 4): Collection
    {
        $query = $this->model->query();
        
        // Apply status filter
        $this->applyStatusFilter($query);
        
        // Order by latest (newest first)
        $query->latest();
        
        return $query->take($limit)->get();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBestSellers(int $limit = 4): Collection
    {
        $query = $this->model->query();
        
        // Apply status filter
        $this->applyStatusFilter($query);
        
        // For now, just use random order as a placeholder for bestsellers
        // In a real app, you would order by sales count or similar metric
        $query->inRandomOrder();
        
        return $query->take($limit)->get();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getRelatedProducts(Product $product, int $limit = 4): Collection
    {
        $query = $this->model->where('id', '!=', $product->id);
        
        // Apply status filter
        $this->applyStatusFilter($query);
        
        // Filter by same categories if possible
        if ($product->categories->count() > 0) {
            $categoryIds = $product->categories->pluck('id')->toArray();
            $query->whereHas('categories', function($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }
        
        return $query->take($limit)->get();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getProductsByCategory(int $categoryId, int $perPage = 12): LengthAwarePaginator
    {
        $query = $this->model->query();
        
        // Apply status filter
        $this->applyStatusFilter($query);
        
        // Filter by category
        $query->whereHas('categories', function($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
        
        return $query->paginate($perPage);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getPaginatedProducts(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = $this->model->query();
        
        // Apply status filter
        $this->applyStatusFilter($query);
        
        // Apply category filter if provided
        if (isset($filters['category'])) {
            $query->whereHas('categories', function($q) use ($filters) {
                $q->where('categories.id', $filters['category']);
            });
        }
        
        // Apply sorting if provided
        if (isset($filters['sort'])) {
            switch ($filters['sort']) {
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
        
        // Apply price filters if provided
        if (isset($filters['price_min'])) {
            $query->where('price', '>=', $filters['price_min']);
        }
        
        if (isset($filters['price_max'])) {
            $query->where('price', '<=', $filters['price_max']);
        }
        
        Log::info('Products query SQL: ' . $query->toSql());
        
        return $query->paginate($perPage);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getAvailableStatusValues(): array
    {
        return $this->model->distinct()->pluck('status')->toArray();
    }
    
    /**
     * {@inheritdoc}
     */
    public function create(array $data): Product
    {
        return $this->model->create($data);
    }
    
    /**
     * {@inheritdoc}
     */
    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }
    
    /**
     * {@inheritdoc}
     */
    public function delete(Product $product): bool
    {
        return $product->delete();
    }
    
    /**
     * Apply status filter to a query
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    protected function applyStatusFilter($query): void
    {
        $statusValues = $this->getAvailableStatusValues();
        
        if (in_array('published', $statusValues)) {
            $query->where('status', 'published');
        } elseif (in_array('active', $statusValues)) {
            $query->where('status', 'active');
        }
    }
} 