<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request): View
    {
        $query = Order::query();
        
        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Search by order number or customer name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }
        
        // Order by requested field or default to newest
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);
        
        $orders = $query->paginate(15);
        
        return view('store.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(string $id): View
    {
        $order = Order::with(['orderItems.product'])->findOrFail($id);
        return view('store.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(string $id): View
    {
        $order = Order::with(['orderItems.product'])->findOrFail($id);
        return view('store.orders.edit', compact('order'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,refunded',
            'payment_status' => 'required|in:paid,pending,failed,refunded',
            'notes' => 'nullable|string',
            'tracking_number' => 'nullable|string|max:100',
            'shipping_provider' => 'nullable|string|max:100',
        ]);
        
        $order->update($validated);
        
        return redirect()->route('store.orders.show', $order->id)
            ->with('success', 'Order updated successfully');
    }

    /**
     * Generate an invoice for the order.
     */
    public function invoice(string $id): View
    {
        $order = Order::with(['orderItems.product'])->findOrFail($id);
        return view('store.orders.invoice', compact('order'));
    }
} 