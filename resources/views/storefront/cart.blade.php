@extends('layouts.storefront')

@section('title', 'Shopping Cart')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Your Shopping Cart</h1>
    
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Cart items -->
        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6" id="cart-container">
                    <!-- Empty cart state -->
                    <div id="empty-cart" class="text-center py-8">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-shopping-cart text-5xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-700 mb-2">Your cart is empty</h2>
                        <p class="text-gray-600 mb-6">Looks like you haven't added any products to your cart yet.</p>
                        <a href="{{ route('storefront.products.index') }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                            Browse Products
                        </a>
                    </div>
                    
                    <!-- Cart items list (hidden by default, would be shown when items are added) -->
                    <div id="cart-items" class="hidden">
                        <div class="border-b border-gray-200 pb-4 mb-4 hidden md:flex text-gray-600 text-sm">
                            <div class="w-1/2">Product</div>
                            <div class="w-1/6 text-center">Price</div>
                            <div class="w-1/6 text-center">Quantity</div>
                            <div class="w-1/6 text-right">Total</div>
                        </div>
                        
                        <!-- Cart items would be dynamically inserted here -->
                        
                    </div>
                    
                    <!-- Cart actions (hidden in empty state) -->
                    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center hidden" id="cart-actions">
                        <a href="{{ route('storefront.products') }}" class="text-blue-600 hover:underline mb-4 sm:mb-0 flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
                        </a>
                        <button id="update-cart" class="bg-gray-200 text-gray-800 py-2 px-4 rounded hover:bg-gray-300">
                            Update Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Cart summary (hidden in empty state) -->
        <div class="w-full lg:w-1/3 hidden" id="cart-summary">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium" id="cart-subtotal">$0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping (estimated)</span>
                        <span class="font-medium">$5.99</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax (estimated)</span>
                        <span class="font-medium" id="cart-tax">$0.00</span>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold">Total</span>
                        <span class="text-xl font-bold" id="cart-total">$0.00</span>
                    </div>
                </div>
                
                <a href="#" class="block w-full bg-blue-600 text-white text-center py-3 px-4 rounded-md hover:bg-blue-700 transition-colors">
                    Proceed to Checkout
                </a>
                
                <div class="mt-6">
                    <h3 class="text-gray-700 font-medium mb-2">Have a promo code?</h3>
                    <div class="flex">
                        <input type="text" placeholder="Enter code" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-r-md hover:bg-gray-300">
                            Apply
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // In a real application, this would check if there are items in the cart
        // and show/hide elements accordingly
        const hasItems = false; // This would be dynamic in a real app
        
        if (hasItems) {
            document.getElementById('empty-cart').classList.add('hidden');
            document.getElementById('cart-items').classList.remove('hidden');
            document.getElementById('cart-actions').classList.remove('hidden');
            document.getElementById('cart-summary').classList.remove('hidden');
        }
    });
</script>
@endpush 