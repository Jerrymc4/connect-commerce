<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    /**
     * Get all orders
     * 
     * @return Collection
     */
    public function getAll(): Collection;
    
    /**
     * Get an order by ID
     * 
     * @param int $id
     * @return Order|null
     */
    public function getById(int $id): ?Order;
    
    /**
     * Get orders by customer ID
     * 
     * @param int $customerId
     * @return Collection
     */
    public function getByCustomerId(int $customerId): Collection;
    
    /**
     * Get recent orders by customer ID
     * 
     * @param int $customerId
     * @param int $limit
     * @return Collection
     */
    public function getRecentOrdersByCustomerId(int $customerId, int $limit = 3): Collection;
    
    /**
     * Get paginated orders by customer ID
     * 
     * @param int $customerId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedOrdersByCustomerId(int $customerId, int $perPage = 10): LengthAwarePaginator;
    
    /**
     * Create a new order
     * 
     * @param array $data
     * @return Order
     */
    public function create(array $data): Order;
    
    /**
     * Update an order
     * 
     * @param Order $order
     * @param array $data
     * @return bool
     */
    public function update(Order $order, array $data): bool;
    
    /**
     * Delete an order
     * 
     * @param Order $order
     * @return bool
     */
    public function delete(Order $order): bool;
    
    /**
     * Get customer order with ID validation
     * 
     * @param int $orderId
     * @param int $customerId
     * @return Order|null
     */
    public function getCustomerOrder(int $orderId, int $customerId): ?Order;
} 