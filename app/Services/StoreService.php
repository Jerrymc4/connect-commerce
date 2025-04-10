<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StoreService
{
    /**
     * Get all stores
     *
     * @return Collection
     */
    public function getAllStores(): Collection
    {
        return Store::all();
    }

    /**
     * Get paginated stores
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedStores(int $perPage = 15): LengthAwarePaginator
    {
        return Store::paginate($perPage);
    }

    /**
     * Get store by ID
     *
     * @param int $id
     * @return Store|null
     */
    public function getStoreById(string $id): ?Store
    {
        return Store::find($id);
    }

    /**
     * Get store by domain
     *
     * @param string $domain
     * @return Store|null
     */
    public function getStoreByDomain(string $domain): ?Store
    {
        return Store::whereHas('domains', function ($query) use ($domain) {
            $query->where('domain', $domain);
        })->first();
    }

    /**
     * Get store by owner ID
     *
     * @param int $ownerId
     * @return Store|null
     */
    public function getStoreByOwnerId(int $ownerId): ?Store
    {
        // Since we've set up array casting for the data column,
        // we can use Laravel's JSON querying capabilities
        return Store::where('data->user_id', $ownerId)->first();
    }

    /**
     * Get active stores
     *
     * @return Collection
     */
    public function getActiveStores(): Collection
    {
        return Store::all(); // In a real app, you might filter by a status field
    }

    /**
     * Create new store
     *
     * @param array $data
     * @return Store
     */
    public function createStore(array $data): Store
    {
        return Store::create($data);
    }

    /**
     * Update store
     *
     * @param array $data
     * @param string $id
     * @return Store
     */
    public function updateStore(array $data, string $id): Store
    {
        $store = Store::findOrFail($id);
        $store->update($data);
        return $store;
    }

    /**
     * Delete store
     *
     * @param string $id
     * @return bool
     */
    public function deleteStore(string $id): bool
    {
        return Store::destroy($id);
    }
} 