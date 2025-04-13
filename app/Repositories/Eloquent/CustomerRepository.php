<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * @var Customer
     */
    protected $model;
    
    /**
     * CustomerRepository constructor.
     *
     * @param Customer $model
     */
    public function __construct(Customer $model)
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
    public function getById(int $id): ?Customer
    {
        return $this->model->find($id);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getByEmail(string $email): ?Customer
    {
        return $this->model->where('email', $email)->first();
    }
    
    /**
     * {@inheritdoc}
     */
    public function updateProfile(Customer $customer, array $data): bool
    {
        return $this->model->where('id', $customer->id)->update($data);
    }
    
    /**
     * {@inheritdoc}
     */
    public function updatePassword(Customer $customer, string $password): bool
    {
        return $this->model->where('id', $customer->id)->update([
            'password' => Hash::make($password)
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function create(array $data): Customer
    {
        return $this->model->create($data);
    }
    
    /**
     * {@inheritdoc}
     */
    public function delete(Customer $customer): bool
    {
        return $customer->delete();
    }
    
    /**
     * {@inheritdoc}
     */
    public function searchCustomers(array $filters = [], string $sort = 'created_at', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();
        
        // Apply search filter if provided
        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Apply sort
        $query->orderBy($sort, $direction);
        
        return $query->paginate($perPage);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCustomerWithOrders(int $id): ?Customer
    {
        return $this->model->with(['orders' => function($query) {
            $query->latest();
        }])->find($id);
    }
    
    /**
     * {@inheritdoc}
     */
    public function updateOrCreateAddress(Customer $customer, array $addressData, string $type = 'both', bool $isDefault = true): void
    {
        $addressData['type'] = $type;
        $addressData['is_default'] = $isDefault;
        
        $defaultAddress = $customer->addresses()
            ->where('is_default', true)
            ->where('type', $type)
            ->first();
            
        if ($defaultAddress) {
            $defaultAddress->update($addressData);
        } else {
            $customer->addresses()->create($addressData);
        }
    }
} 