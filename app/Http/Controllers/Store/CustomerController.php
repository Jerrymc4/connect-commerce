<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request): View
    {
        $query = User::where('role', 'customer');
        
        // Search by name or email
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Sort customers
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);
        
        $customers = $query->paginate(15);
        
        return view('store.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): View
    {
        return view('store.customers.form');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);
        
        // Encrypt the password
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'customer';
        
        User::create($validated);
        
        return redirect()->route('store.customers')
            ->with('success', 'Customer created successfully');
    }

    /**
     * Display the specified customer.
     */
    public function show(string $id): View
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        $orders = $customer->orders()->latest()->get();
        
        return view('store.customers.show', compact('customer', 'orders'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(string $id): View
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        return view('store.customers.form', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);
        
        // Only update password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $customer->update($validated);
        
        return redirect()->route('store.customers')
            ->with('success', 'Customer updated successfully');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(string $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        $customer->delete();
        
        return redirect()->route('store.customers')
            ->with('success', 'Customer deleted successfully');
    }
} 