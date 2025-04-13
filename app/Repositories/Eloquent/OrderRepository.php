<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * @var Order
     */
    protected $model;
    
    /**
     * OrderRepository constructor.
     *
     * @param Order $model
     */
    public function __construct(Order $model)
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
    public function getById(int $id): ?Order
    {
        return $this->model->find($id);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getByCustomerId(int $customerId): Collection
    {
        return $this->model->where('customer_id', $customerId)->get();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getRecentOrdersByCustomerId(int $customerId, int $limit = 3): Collection
    {
        return $this->model->where('customer_id', $customerId)
            ->latest()
            ->take($limit)
            ->get();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getPaginatedOrdersByCustomerId(int $customerId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('customer_id', $customerId)
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * {@inheritdoc}
     */
    public function create(array $data): Order
    {
        return $this->model->create($data);
    }
    
    /**
     * {@inheritdoc}
     */
    public function update(Order $order, array $data): bool
    {
        return $order->update($data);
    }
    
    /**
     * {@inheritdoc}
     */
    public function delete(Order $order): bool
    {
        return $order->delete();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCustomerOrder(int $orderId, int $customerId): ?Order
    {
        return $this->model->where('customer_id', $customerId)
            ->where('id', $orderId)
            ->first();
    }
} 