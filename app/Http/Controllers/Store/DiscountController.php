<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Support\Str;

class DiscountController extends Controller
{
    /**
     * Display a listing of discounts.
     */
    public function index(Request $request): View
    {
        $query = Discount::query();
        
        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Search by code or name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        // Order by requested field or default to newest
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);
        
        $discounts = $query->paginate(15);
        
        return view('store.discounts.index', compact('discounts'));
    }

    /**
     * Show the form for creating a new discount.
     */
    public function create(): View
    {
        $products = Product::select('id', 'name')->get();
        return view('store.discounts.form', compact('products'));
    }

    /**
     * Store a newly created discount in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:discounts',
            'type' => 'required|in:percentage,fixed_amount,free_shipping',
            'value' => 'required_if:type,percentage,fixed_amount|nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:0',
            'individual_use_only' => 'boolean',
            'exclude_sale_items' => 'boolean',
            'status' => 'required|in:active,inactive',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);
        
        // Generate a code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = strtoupper(Str::random(8));
        } else {
            $validated['code'] = strtoupper($validated['code']);
        }
        
        $discount = Discount::create($validated);
        
        // Attach selected products if any
        if (isset($validated['products'])) {
            $discount->products()->attach($validated['products']);
        }
        
        return redirect()->route('store.discounts')
            ->with('success', 'Discount created successfully');
    }

    /**
     * Show the form for editing the specified discount.
     */
    public function edit(string $id): View
    {
        $discount = Discount::with('products')->findOrFail($id);
        $products = Product::select('id', 'name')->get();
        return view('store.discounts.form', compact('discount', 'products'));
    }

    /**
     * Update the specified discount in storage.
     */
    public function update(Request $request, string $id)
    {
        $discount = Discount::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:discounts,code,' . $id,
            'type' => 'required|in:percentage,fixed_amount,free_shipping',
            'value' => 'required_if:type,percentage,fixed_amount|nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:0',
            'individual_use_only' => 'boolean',
            'exclude_sale_items' => 'boolean',
            'status' => 'required|in:active,inactive',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);
        
        // Convert code to uppercase
        $validated['code'] = strtoupper($validated['code']);
        
        $discount->update($validated);
        
        // Sync selected products
        if (isset($validated['products'])) {
            $discount->products()->sync($validated['products']);
        } else {
            $discount->products()->detach();
        }
        
        return redirect()->route('store.discounts')
            ->with('success', 'Discount updated successfully');
    }

    /**
     * Remove the specified discount from storage.
     */
    public function destroy(string $id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();
        
        return redirect()->route('store.discounts')
            ->with('success', 'Discount deleted successfully');
    }
} 