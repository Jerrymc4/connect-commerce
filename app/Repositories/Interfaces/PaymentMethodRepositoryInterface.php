<?php

namespace App\Repositories\Interfaces;

use App\Models\PaymentMethod;
use Illuminate\Support\Collection;

interface PaymentMethodRepositoryInterface
{
    /**
     * Get all payment methods
     * 
     * @return Collection
     */
    public function getAll(): Collection;
    
    /**
     * Get a payment method by ID
     * 
     * @param int $id
     * @return PaymentMethod|null
     */
    public function getById(int $id): ?PaymentMethod;
    
    /**
     * Get payment methods by customer ID
     * 
     * @param int $customerId
     * @return Collection
     */
    public function getByCustomerId(int $customerId): Collection;
    
    /**
     * Get customer payment method with ID validation
     * 
     * @param int $paymentMethodId
     * @param int $customerId
     * @return PaymentMethod|null
     */
    public function getCustomerPaymentMethod(int $paymentMethodId, int $customerId): ?PaymentMethod;
    
    /**
     * Create a new payment method
     * 
     * @param array $data
     * @return PaymentMethod
     */
    public function create(array $data): PaymentMethod;
    
    /**
     * Set a payment method as default
     * 
     * @param PaymentMethod $paymentMethod
     * @return bool
     */
    public function setAsDefault(PaymentMethod $paymentMethod): bool;
    
    /**
     * Delete a payment method
     * 
     * @param PaymentMethod $paymentMethod
     * @return bool
     */
    public function delete(PaymentMethod $paymentMethod): bool;
} 