@extends('layouts.storefront')

@section('title', 'Guest Checkout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Main checkout column -->
        <div class="w-full md:w-2/3">
            <div class="flex items-center mb-6">
                <h1 class="text-2xl font-bold">Guest Checkout</h1>
                <a href="{{ route('storefront.checkout') }}" class="ml-auto text-blue-600 hover:underline flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Your Information</h2>
                
                <form method="POST" action="{{ route('storefront.checkout.guest.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address <span class="text-red-600">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required autofocus>
                        @error('email')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">We'll send your order confirmation to this email.</p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Full Name <span class="text-red-600">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                        @error('name')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="phone" class="block text-gray-700 text-sm font-medium mb-2">Phone Number (optional)</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                        @error('phone')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">For delivery questions only.</p>
                    </div>
                    
                    <div class="flex items-center mb-6">
                        <input type="checkbox" name="marketing_opt_in" id="marketing_opt_in" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="marketing_opt_in" class="ml-2 block text-sm text-gray-700">
                            Keep me up to date on news and exclusive offers
                        </label>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <p class="mb-4 text-gray-600">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>
                            Your information is secure and will only be used for this order.
                        </p>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <a href="{{ route('customer.login') }}" class="text-blue-600 hover:underline">
                            Already have an account? Sign in
                        </a>
                        <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Continue to Shipping
                        </button>
                    </div>
                </form>
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