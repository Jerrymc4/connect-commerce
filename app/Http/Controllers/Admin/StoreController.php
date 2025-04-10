<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StoreService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController extends Controller
{
    protected $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    /**
     * Display a listing of the stores.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('admin.stores.index', [
            'stores' => $this->storeService->getPaginatedStores(10)
        ]);
    }

    /**
     * Show the form for creating a new store.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('admin.stores.create');
    }

    /**
     * Store a newly created store in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:domains,domain|regex:/^[a-z0-9\-]+$/',
            'email' => 'required|email',
        ]);

        $store = $this->storeService->createStore([
            'name' => $validated['name'],
        ]);

        // Add domain to the store
        $store->domains()->create([
            'domain' => $validated['domain']
        ]);

        return redirect()->route('admin.stores.index')
            ->with('success', 'Store created successfully.');
    }

    /**
     * Display the specified store.
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $store = $this->storeService->getStoreById($id);
        
        if (!$store) {
            return redirect()->route('admin.stores.index')
                ->with('error', 'Store not found.');
        }

        return view('admin.stores.show', [
            'store' => $store
        ]);
    }

    /**
     * Show the form for editing the specified store.
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $store = $this->storeService->getStoreById($id);
        
        if (!$store) {
            return redirect()->route('admin.stores.index')
                ->with('error', 'Store not found.');
        }

        return view('admin.stores.edit', [
            'store' => $store
        ]);
    }

    /**
     * Update the specified store in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $store = $this->storeService->getStoreById($id);
        
        if (!$store) {
            return redirect()->route('admin.stores.index')
                ->with('error', 'Store not found.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'business_type' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'status' => 'nullable|string|in:active,inactive,suspended',
        ]);

        $this->storeService->updateStore($validated, $id);

        return redirect()->route('admin.stores.index')
            ->with('success', 'Store updated successfully.');
    }

    /**
     * Remove the specified store from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $store = $this->storeService->getStoreById($id);
        
        if (!$store) {
            return redirect()->route('admin.stores.index')
                ->with('error', 'Store not found.');
        }

        $this->storeService->deleteStore($id);

        return redirect()->route('admin.stores.index')
            ->with('success', 'Store deleted successfully.');
    }
} 