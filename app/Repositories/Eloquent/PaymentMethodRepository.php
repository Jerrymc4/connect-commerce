<?php

namespace App\Repositories\Eloquent;

use App\Models\PaymentMethod;
use App\Repositories\Interfaces\PaymentMethodRepositoryInterface;
use Illuminate\Support\Collection;

class PaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    /**
     * @var PaymentMethod
     */
    protected $model;
    
    /**
     * PaymentMethodRepository constructor.
     *
     * @param PaymentMethod $model
     */
    public function __construct(PaymentMethod $model)
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
    public function getById(int $id): ?PaymentMethod
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
    public function getCustomerPaymentMethod(int $paymentMethodId, int $customerId): ?PaymentMethod
    {
        return $this->model->where('customer_id', $customerId)
            ->where('id', $paymentMethodId)
            ->first();
    }
    
    /**
     * {@inheritdoc}
     */
    public function create(array $data): PaymentMethod
    {
        return $this->model->create($data);
    }
    
    /**
     * {@inheritdoc}
     */
    public function setAsDefault(PaymentMethod $paymentMethod): bool
    {
        // First update the payment method to be default
        $updated = $paymentMethod->update(['is_default' => true]);
        
        if ($updated) {
            // Make all other payment methods non-default
            $this->model->where('customer_id', $paymentMethod->customer_id)
                ->where('id', '!=', $paymentMethod->id)
                ->update(['is_default' => false]);
        }
        
        return $updated;
    }
    
    /**
     * {@inheritdoc}
     */
    public function delete(PaymentMethod $paymentMethod): bool
    {
        return $paymentMethod->delete();
    }
} 