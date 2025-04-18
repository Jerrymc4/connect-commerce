<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderHistory;
use App\Models\Product;
use App\Services\AuditLogService;

class CheckoutController extends Controller
{
    protected $auditLogService;
    
    /**
     * Create a new controller instance.
     *
     * @param AuditLogService $auditLogService
     */
    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }
    
    /**
     * Display the checkout page
     */
    public function index()
    {
        // Get the cart from session
        $cart = session()->get('cart', []);
        
        // If cart is empty, redirect to cart page
        if (empty($cart)) {
            return redirect()->route('storefront.cart')
                ->with('error', 'Your cart is empty. Please add items before checking out.');
        }
        
        // Calculate cart totals
        $subtotal = 0;
        $total = 0;
        $tax = 0;
        $shipping = 0;
        
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Add tax (example: 10%)
        $tax = $subtotal * 0.10;
        
        // Add shipping (example: flat rate of $5)
        $shipping = 5.00;
        
        // Calculate total
        $total = $subtotal + $tax + $shipping;
        
        // Get customer details if logged in
        $customer = null;
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
        }
        
        return view('storefront.checkout', compact('cart', 'subtotal', 'tax', 'shipping', 'total', 'customer'));
    }
    
    /**
     * Process the checkout
     */
    public function process(Request $request)
    {
        // Validate checkout form
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|string|in:credit_card,paypal',
        ]);
        
        // Get cart from session
        $cart = session()->get('cart', []);
        
        // If cart is empty, redirect to cart page
        if (empty($cart)) {
            return redirect()->route('storefront.cart')
                ->with('error', 'Your cart is empty. Please add items before checking out.');
        }
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $taxAmount = $subtotal * 0.10; // 10% tax
        $shippingAmount = 5.00; // Flat $5 shipping
        $total = $subtotal + $taxAmount + $shippingAmount;
        
        // Process payment (placeholder - assume payment successful)
        $paymentSuccessful = true;
        
        if ($paymentSuccessful) {
            // Create order
            $order = new Order();
            $order->order_number = Order::generateOrderNumber();
            $order->customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
            $order->status = 'pending';
            $order->payment_status = 'paid';
            $order->payment_method = $request->payment_method;
            $order->shipping_method = 'standard';
            $order->subtotal = $subtotal;
            $order->tax_amount = $taxAmount;
            $order->shipping_amount = $shippingAmount;
            $order->discount_amount = 0;
            $order->total = $total;
            $order->notes = 'Order placed through website checkout.';
            
            // Customer information
            $order->customer_name = $request->first_name . ' ' . $request->last_name;
            $order->customer_email = $request->email;
            $order->customer_phone = $request->phone;
            
            // Shipping information
            $order->shipping_address = $request->address;
            $order->shipping_city = $request->city;
            $order->shipping_state = $request->state;
            $order->shipping_zip = $request->postal_code;
            $order->shipping_country = $request->country;
            
            // Use shipping as billing for now
            $order->billing_address = $request->address;
            $order->billing_city = $request->city;
            $order->billing_state = $request->state;
            $order->billing_zip = $request->postal_code;
            $order->billing_country = $request->country;
            
            // Save the order
            $order->save();
            
            // Add order items
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                
                if ($product) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $product->id;
                    $orderItem->product_name = $item['name'];
                    $orderItem->sku = $product->sku ?? '';
                    $orderItem->price = $item['price'];
                    $orderItem->quantity = $item['quantity'];
                    $orderItem->options = $item['options'] ?? [];
                    $orderItem->save();
                    
                    // Update product stock if needed
                    if ($product->track_inventory && isset($product->stock_quantity)) {
                        $product->stock_quantity -= $item['quantity'];
                        $product->save();
                    }
                }
            }
            
            // Add order history
            OrderHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'comment' => 'Order placed through website',
                'user_id' => Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null,
                'user_type' => Auth::guard('customer')->check() ? 'customer' : 'guest',
            ]);
            
            // Log the order creation
            try {
                $this->auditLogService->logCreated($order, ['order', 'checkout']);
            } catch (\Exception $e) {
                // Just log the error but don't stop the checkout process
                error_log('Error logging order creation: ' . $e->getMessage());
            }
            
            // Clear cart
            session()->forget('cart');
            
            // Redirect to order confirmation
            return redirect()->route('storefront.home')
                ->with('success', 'Your order has been placed successfully! Order #' . $order->order_number);
        } else {
            // Redirect back with error
            return redirect()->back()
                ->with('error', 'Payment failed. Please try again.');
        }
    }
} 