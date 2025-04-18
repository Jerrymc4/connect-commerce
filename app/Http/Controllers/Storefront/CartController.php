<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        return view('storefront.cart');
    }

    /**
     * Add a product to the cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'options' => 'nullable|array',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if (isset($product->stock_quantity) && $product->stock_quantity > 0 && $request->quantity > $product->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Requested quantity exceeds available stock'
            ]);
        }

        $cart = session()->get('cart', []);
        
        // If product is already in cart, update quantity
        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] += $request->quantity;
        } else {
            // Add product to cart
            $cart[$request->product_id] = [
                'name' => $product->name,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'image' => $product->images && $product->images->isNotEmpty() ? $product->images->first()->url : null,
                'options' => $request->options ?? []
            ];
            
            // Add compare price if available
            if (isset($product->compare_price) && $product->compare_price > 0) {
                $cart[$request->product_id]['compare_price'] = $product->compare_price;
            }
        }
        
        session()->put('cart', $cart);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => count($cart)
            ]);
        }
        
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update cart quantities
     */
    public function update(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
        ]);
        
        $cart = session()->get('cart', []);
        
        foreach ($request->items as $id => $quantity) {
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] = max(1, (int) $quantity);
            }
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!'
        ]);
    }

    /**
     * Remove an item from the cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart successfully!'
        ]);
    }

    /**
     * Clear the entire cart
     */
    public function clear()
    {
        session()->forget('cart');
        
        return redirect()->route('storefront.cart')->with('success', 'Cart cleared successfully!');
    }
} 