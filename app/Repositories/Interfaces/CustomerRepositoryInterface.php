<?php

namespace App\Repositories\Interfaces;

use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CustomerRepositoryInterface
{
    /**
     * Get all customers
     * 
     * @return Collection
     */
    public function getAll(): Collection;
    
    /**
     * Get a customer by ID
     * 
     * @param int $id
     * @return Customer|null
     */
    public function getById(int $id): ?Customer;
    
    /**
     * Get a customer by email
     * 
     * @param string $email
     * @return Customer|null
     */
    public function getByEmail(string $email): ?Customer;
    
    /**
     * Update customer profile
     * 
     * @param Customer $customer
     * @param array $data
     * @return bool
     */
    public function updateProfile(Customer $customer, array $data): bool;
    
    /**
     * Update customer password
     * 
     * @param Customer $customer
     * @param string $password
     * @return bool
     */
    public function updatePassword(Customer $customer, string $password): bool;
    
    /**
     * Create a new customer
     * 
     * @param array $data
     * @return Customer
     */
    public function create(array $data): Customer;
    
    /**
     * Delete a customer
     * 
     * @param Customer $customer
     * @return bool
     */
    public function delete(Customer $customer): bool;
    
    /**
     * Search customers with filters and pagination
     * 
     * @param array $filters
     * @param string $sort
     * @param string $direction
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchCustomers(array $filters = [], string $sort = 'created_at', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator;
    
    /**
     * Get customer with orders
     * 
     * @param int $id
     * @return Customer|null
     */
    public function getCustomerWithOrders(int $id): ?Customer;
    
    /**
     * Update a customer's address or create if not exists
     * 
     * @param Customer $customer
     * @param array $addressData
     * @param string $type
     * @param bool $isDefault
     * @return void
     */
    public function updateOrCreateAddress(Customer $customer, array $addressData, string $type = 'both', bool $isDefault = true): void;
} 