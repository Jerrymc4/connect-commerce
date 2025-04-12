<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Address;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request): View
    {
        $query = Customer::query();
        
        // Search by name or email
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);
        
        // Extract address data from validated input
        $addressData = null;
        if ($validated['address_line_1'] ?? false) {
            $addressData = [
                'address_line_1' => $validated['address_line_1'],
                'address_line_2' => $validated['address_line_2'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'zipcode' => $validated['zipcode'] ?? null,
                'country' => $validated['country'] ?? null,
                'is_default' => true,
                'type' => 'both' // Both shipping and billing
            ];
            
            // Remove address fields from validated data before creating customer
            unset(
                $validated['address_line_1'],
                $validated['address_line_2'],
                $validated['city'],
                $validated['state'],
                $validated['zipcode'],
                $validated['country']
            );
        }
        
        // Create customer with remaining validated data
        $customer = Customer::create($validated);
        
        // If we have address data, create the address
        if ($addressData) {
            $customer->addresses()->create($addressData);
        }
        
        return redirect()->route('store.customers')
            ->with('success', 'Customer created successfully');
    }

    /**
     * Display the specified customer.
     */
    public function show(string $id): View
    {
        $customer = Customer::findOrFail($id);
        $orders = $customer->orders()->latest()->get();
        
        return view('store.customers.show', compact('customer', 'orders'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(string $id): View
    {
        $customer = Customer::findOrFail($id);
        return view('store.customers.form', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);
        
        // Extract address data from validated input
        $addressData = null;
        if ($validated['address_line_1'] ?? false) {
            $addressData = [
                'address_line_1' => $validated['address_line_1'],
                'address_line_2' => $validated['address_line_2'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'zipcode' => $validated['zipcode'] ?? null,
                'country' => $validated['country'] ?? null,
                'is_default' => true,
                'type' => 'both' // Both shipping and billing
            ];
            
            // Remove address fields from validated data before updating customer
            unset(
                $validated['address_line_1'],
                $validated['address_line_2'],
                $validated['city'],
                $validated['state'],
                $validated['zipcode'],
                $validated['country']
            );
        }
        
        // Only update password if provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        }
        
        // Update customer with validated data
        $customer->update($validated);
        
        // Update or create the default address
        if ($addressData) {
            $defaultAddress = $customer->addresses()
                ->where('is_default', true)
                ->where('type', 'both')
                ->first();
                
            if ($defaultAddress) {
                $defaultAddress->update($addressData);
            } else {
                $customer->addresses()->create($addressData);
            }
        }
        
        return redirect()->route('store.customers')
            ->with('success', 'Customer updated successfully');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        
        return redirect()->route('store.customers')
            ->with('success', 'Customer deleted successfully');
    }
} 