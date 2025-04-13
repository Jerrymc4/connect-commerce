@extends('layouts.storefront')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Main checkout column -->
        <div class="w-full md:w-2/3">
            <h1 class="text-2xl font-bold mb-6">Checkout</h1>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">How would you like to checkout?</h2>
                
                @auth('customer')
                    <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                        <p class="text-green-800">
                            <i class="fas fa-check-circle mr-2"></i>
                            You're signed in as <strong>{{ Auth::guard('customer')->user()->name }}</strong>
                        </p>
                        <a href="{{ route('storefront.checkout.shipping') }}" class="mt-2 inline-block text-blue-600 hover:underline">
                            Continue to shipping
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Guest checkout option -->
                        <div class="border rounded-lg p-6 hover:border-blue-500 transition-colors">
                            <h3 class="text-lg font-medium mb-2">Guest Checkout</h3>
                            <p class="text-gray-600 mb-4">No account necessary. You can create one later.</p>
                            <a href="{{ route('storefront.checkout.guest') }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 inline-block">
                                Continue as Guest
                            </a>
                        </div>
                        
                        <!-- Account checkout option -->
                        <div class="border rounded-lg p-6 hover:border-blue-500 transition-colors">
                            <h3 class="text-lg font-medium mb-2">Sign In</h3>
                            <p class="text-gray-600 mb-4">Already have an account? Sign in for a faster checkout.</p>
                            <a href="{{ route('customer.login', ['redirect' => 'checkout']) }}" class="bg-gray-800 text-white py-2 px-4 rounded hover:bg-gray-900 inline-block">
                                Sign In
                            </a>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <p class="text-gray-600">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>
                            Your information is secure and will only be used for this order.
                        </p>
                    </div>
                @endauth
            </div>
        </div>
        
        <!-- Order summary column -->
        <div class="w-full md:w-1/3">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                
                <div class="border-b border-gray-200 pb-4 mb-4">
                    <div id="cart-items">
                        <!-- Cart items will be loaded dynamically -->
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-800">Cart Items:</span>
                            <span class="text-gray-600">Loading...</span>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium" id="cart-subtotal">$0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium" id="cart-shipping">$0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-medium" id="cart-tax">$0.00</span>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4 mb-4">
                    <div class="flex justify-between">
                        <span class="text-lg font-bold">Total</span>
                        <span class="text-lg font-bold" id="cart-total">$0.00</span>
                    </div>
                </div>
                
                <a href="{{ route('storefront.cart') }}" class="text-blue-600 hover:underline flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Edit Cart
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // This would be replaced with actual cart data loading logic
    document.addEventListener('DOMContentLoaded', function() {
        // Example cart data
        const cartItems = [
            { name: 'Product 1', price: 29.99, quantity: 1 },
            { name: 'Product 2', price: 49.99, quantity: 2 }
        ];
        
        // Update cart summary
        updateCartSummary(cartItems);
    });
    
    function updateCartSummary(items) {
        // Calculate totals
        const subtotal = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const shipping = subtotal > 0 ? 5.99 : 0;
        const tax = subtotal * 0.07;
        const total = subtotal + shipping + tax;
        
        // Update DOM
        document.getElementById('cart-subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('cart-shipping').textContent = '$' + shipping.toFixed(2);
        document.getElementById('cart-tax').textContent = '$' + tax.toFixed(2);
        document.getElementById('cart-total').textContent = '$' + total.toFixed(2);
        
        // Render cart items
        const cartItemsContainer = document.getElementById('cart-items');
        cartItemsContainer.innerHTML = '';
        
        items.forEach(item => {
            const itemRow = document.createElement('div');
            itemRow.className = 'flex justify-between items-center mb-2';
            itemRow.innerHTML = `
                <span class="text-gray-800">${item.name} × ${item.quantity}</span>
                <span class="text-gray-600">$${(item.price * item.quantity).toFixed(2)}</span>
            `;
            cartItemsContainer.appendChild(itemRow);
        });
    }
</script>
@endpush 