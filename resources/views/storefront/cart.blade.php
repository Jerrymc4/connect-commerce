@extends('layouts.storefront')

@section('title', 'Your Cart')

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-primary">Your Cart</h1>
        <div class="mt-8">
            @if(session('cart') && count(session('cart')) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Product
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Price
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach(session('cart') as $id => $details)
                                    <tr data-id="{{ $id }}" class="cart-item">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-md">
                                                    <img class="h-full w-full object-cover object-center" src="{{ $details['image'] ?? 'https://via.placeholder.com/150' }}" alt="{{ $details['name'] }}">
                                                </div>
                                                <div class="ml-4 flex flex-col">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <a href="{{ route('storefront.products.show', $id) }}" class="hover:text-blue-600">
                                                            {{ $details['name'] }}
                                                        </a>
                                                    </div>
                                                    @if(isset($details['options']) && !empty($details['options']))
                                                        <div class="mt-1 text-xs text-gray-500">
                                                            @foreach($details['options'] as $key => $value)
                                                                <span class="block">{{ ucfirst($key) }}: {{ $value }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <div class="mt-1">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                            In Stock
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">${{ number_format($details['price'], 2) }}</div>
                                            @if(isset($details['compare_price']) && $details['compare_price'] > $details['price'])
                                                <div class="text-xs line-through text-gray-500">${{ number_format($details['compare_price'], 2) }}</div>
                                                <div class="text-xs text-red-600">Save {{ number_format((1 - $details['price']/$details['compare_price']) * 100) }}%</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="custom-number-input h-10 w-32">
                                                <div class="flex flex-row h-8 w-full rounded-lg relative bg-transparent">
                                                    <button type="button" data-action="decrement" class="decrement-quantity bg-gray-100 text-gray-600 hover:text-gray-700 hover:bg-gray-200 h-full w-8 rounded-l cursor-pointer flex items-center justify-center">
                                                        <span class="m-auto text-base font-thin">−</span>
                                                    </button>
                                                    <input type="number" data-id="{{ $id }}" value="{{ $details['quantity'] }}" min="1" max="99" class="quantity outline-none focus:outline-none text-center w-full bg-gray-100 font-semibold text-md hover:text-black focus:text-black md:text-base cursor-default flex items-center text-gray-700">
                                                    <button type="button" data-action="increment" class="increment-quantity bg-gray-100 text-gray-600 hover:text-gray-700 hover:bg-gray-200 h-full w-8 rounded-r cursor-pointer flex items-center justify-center">
                                                        <span class="m-auto text-base font-thin">+</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            $<span class="item-total">{{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-medium">
                                            <div class="flex flex-col space-y-2">
                                                <button class="cart-remove text-red-600 hover:text-red-900 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Remove
                                                </button>
                                                <button class="save-for-later text-gray-600 hover:text-gray-900 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                                    </svg>
                                                    Save for later
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="flex justify-between mt-4 items-center">
                    <button id="update-cart" class="py-2 px-4 rounded text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors" style="background-color: var(--primary-color)">
                        Update Cart
                    </button>
                </div>
                
                <div class="mt-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Discount Code Section -->
                    <div class="lg:col-span-7 bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Have a discount code?</h3>
                        <div class="flex">
                            <input type="text" id="discount-code" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:outline-none focus:ring-primary focus:border-primary" placeholder="Enter your code">
                            <button id="apply-discount" class="inline-flex items-center px-4 py-2 border border-transparent rounded-r-md text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2" style="background-color: var(--primary-color)">
                                Apply
                            </button>
                        </div>
                        <div id="discount-message" class="mt-2 text-sm hidden"></div>
                        
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping estimate</h3>
                            <div class="space-y-3">
                                <div>
                                    <select id="shipping-country" class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-primary focus:border-primary">
                                        <option value="">Select country</option>
                                        <option value="US">United States</option>
                                        <option value="CA">Canada</option>
                                        <option value="MX">Mexico</option>
                                        <option value="UK">United Kingdom</option>
                                        <option value="AU">Australia</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <input type="text" id="shipping-postal" class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-primary focus:border-primary" placeholder="Postal code">
                                    </div>
                                    <div>
                                        <button id="calculate-shipping" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2" style="background-color: var(--primary-color)">
                                            Calculate Shipping
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Summary Section -->
                    <div class="lg:col-span-5">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                            
                            <div class="flow-root">
                                <dl class="-my-2 text-sm">
                                    <div class="py-2 flex items-center justify-between">
                                        <dt class="text-gray-600">Subtotal</dt>
                                        <dd class="font-medium text-gray-900">${{ array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, session('cart'))) }}</dd>
                                    </div>
                                    
                                    <div class="py-2 flex items-center justify-between border-t border-gray-200">
                                        <dt class="text-gray-600">Shipping</dt>
                                        <dd id="shipping-cost" class="font-medium text-gray-900">Calculated at checkout</dd>
                                    </div>
                                    
                                    <div id="discount-row" class="py-2 flex items-center justify-between border-t border-gray-200 hidden">
                                        <dt class="text-gray-600">Discount</dt>
                                        <dd id="discount-amount" class="font-medium text-green-600">-$0.00</dd>
                                    </div>
                                    
                                    <div class="py-2 flex items-center justify-between border-t border-gray-200">
                                        <dt class="text-gray-600">Tax</dt>
                                        <dd id="tax-amount" class="font-medium text-gray-900">Calculated at checkout</dd>
                                    </div>
                                    
                                    <div class="py-2 flex items-center justify-between border-t border-b border-gray-200">
                                        <dt class="text-lg font-bold text-gray-900">Total</dt>
                                        <dd id="cart-total" class="text-lg font-bold text-gray-900">${{ array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, session('cart'))) }}</dd>
                                    </div>
                                </dl>
                            </div>
                            
                            <div class="mt-6">
                                <a href="{{ route('storefront.checkout') }}" class="w-full flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2" style="background-color: var(--primary-color)">
                                    Proceed to Checkout
                                </a>
                            </div>
                            
                            <div class="mt-4 flex justify-center">
                                <a href="{{ route('storefront.products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Continue Shopping
                                </a>
                            </div>
                        </div>
                        
                        <!-- Secure checkout badge -->
                        <div class="mt-4 bg-white p-4 rounded-lg shadow-sm">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span class="text-sm text-gray-600">Secure checkout</span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white py-12 px-6 shadow-sm sm:rounded-lg text-center">
                    <div class="text-center">
                        <i class="fas fa-shopping-cart text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                        <p class="text-gray-500 mb-6">Looks like you haven't added any products to your cart yet.</p>
                        <a href="{{ route('storefront.products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2" style="background-color: var(--primary-color)">
                            Start Shopping
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Saved for later section -->
<div id="saved-for-later" class="mt-16 hidden">
    <h2 class="text-xl font-bold text-gray-900">Saved for later</h2>
    <div class="mt-6 bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="divide-y divide-gray-200" id="saved-items-container">
            <!-- Saved items will be dynamically added here by JavaScript -->
        </div>
    </div>
</div>

<!-- Success notification toast -->
<div id="notification" class="fixed top-4 right-4 bg-green-50 border-l-4 border-green-400 p-4 shadow-md rounded-r-md z-50 transform translate-x-full transition-transform duration-300">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p id="notification-message" class="text-sm text-green-700">Cart updated successfully!</p>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button id="close-notification" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cart elements
        const updateCartBtn = document.getElementById('update-cart');
        const cartItems = document.querySelectorAll('.cart-item');
        const removeButtons = document.querySelectorAll('.cart-remove');
        const cartTotal = document.getElementById('cart-total');
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notification-message');
        const closeNotificationBtn = document.getElementById('close-notification');
        const savedForLaterSection = document.getElementById('saved-for-later');
        const savedItemsContainer = document.getElementById('saved-items-container');
        const applyDiscountBtn = document.getElementById('apply-discount');
        const discountCode = document.getElementById('discount-code');
        const discountMessage = document.getElementById('discount-message');
        const discountRow = document.getElementById('discount-row');
        const discountAmount = document.getElementById('discount-amount');
        const calculateShippingBtn = document.getElementById('calculate-shipping');
        const shippingCountry = document.getElementById('shipping-country');
        const shippingPostal = document.getElementById('shipping-postal');
        const shippingCost = document.getElementById('shipping-cost');
        const taxAmount = document.getElementById('tax-amount');
        
        // Saved items storage initialization
        let savedItems = JSON.parse(localStorage.getItem('savedItems')) || {};
        
        // Calculate and update cart totals
        function updateCartTotals() {
            let subtotal = 0;
            
            cartItems.forEach(item => {
                const price = parseFloat(item.querySelector('.item-total').textContent.replace('$', ''));
                subtotal += price;
            });
            
            // Format to 2 decimal places
            subtotal = subtotal.toFixed(2);
            
            // Update the subtotal display
            if (cartTotal) {
                cartTotal.textContent = '$' + subtotal;
            }
        }
        
        // Quantity increment/decrement functionality
        document.querySelectorAll('.decrement-quantity').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity');
                const currentValue = parseInt(input.value);
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                    updateItemTotal(input);
                }
            });
        });
        
        document.querySelectorAll('.increment-quantity').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity');
                const currentValue = parseInt(input.value);
                const maxValue = parseInt(input.getAttribute('max'));
                if (currentValue < maxValue) {
                    input.value = currentValue + 1;
                    updateItemTotal(input);
                }
            });
        });
        
        // Update individual item total when quantity changes
        document.querySelectorAll('.quantity').forEach(input => {
            input.addEventListener('change', function() {
                const min = parseInt(this.getAttribute('min') || 1);
                const max = parseInt(this.getAttribute('max'));
                const value = parseInt(this.value);
                
                // Validate input
                if (isNaN(value) || value < min) {
                    this.value = min;
                } else if (max && value > max) {
                    this.value = max;
                }
                
                updateItemTotal(this);
            });
        });
        
        function updateItemTotal(input) {
            const itemRow = input.closest('tr');
            const priceText = itemRow.querySelector('td:nth-child(2) .text-sm').textContent;
            const price = parseFloat(priceText.replace('$', ''));
            const quantity = parseInt(input.value);
            const totalElement = itemRow.querySelector('.item-total');
            
            totalElement.textContent = (price * quantity).toFixed(2);
            updateCartTotals();
        }
        
        // Update cart via AJAX without page reload
        if (updateCartBtn) {
            updateCartBtn.addEventListener('click', function() {
                const items = document.querySelectorAll('tr[data-id]');
                const cartData = {};
                
                items.forEach(item => {
                    const id = item.getAttribute('data-id');
                    const quantity = item.querySelector('.quantity').value;
                    cartData[id] = quantity;
                });
                
                // Show loading indicator
                updateCartBtn.textContent = 'Updating...';
                updateCartBtn.disabled = true;
                
                // Ajax request to update cart
                fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ items: cartData })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success notification
                        showNotification('Cart updated successfully!');
                        
                        // Reset button
                        updateCartBtn.textContent = 'Update Cart';
                        updateCartBtn.disabled = false;
                        
                        // Update cart totals
                        updateCartTotals();
                    }
                })
                .catch(error => {
                    console.error('Error updating cart:', error);
                    updateCartBtn.textContent = 'Update Cart';
                    updateCartBtn.disabled = false;
                });
            });
        }
        
        // Remove item from cart with AJAX
        if (removeButtons) {
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const id = row.getAttribute('data-id');
                    
                    // Disable button and show loading state
                    button.disabled = true;
                    button.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Removing...';
                    
                    // Ajax request to remove item
                    fetch('/cart/remove', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Add fade-out animation
                            row.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                            
                            // Remove the row after animation completes
                            setTimeout(() => {
                                row.remove();
                                
                                // Update cart totals
                                updateCartTotals();
                                
                                // Show success notification
                                showNotification('Item removed from cart');
                                
                                // If cart is empty, reload to show empty cart state
                                if (document.querySelectorAll('.cart-item').length === 0) {
                                    window.location.reload();
                                }
                            }, 500);
                        }
                    })
                    .catch(error => {
                        console.error('Error removing item:', error);
                        button.disabled = false;
                        button.innerHTML = 'Remove';
                    });
                });
            });
        }
        
        // Save for later functionality
        document.querySelectorAll('.save-for-later').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const id = row.getAttribute('data-id');
                const productImage = row.querySelector('img').src;
                const productName = row.querySelector('.text-sm.font-medium').textContent.trim();
                const productPrice = row.querySelector('td:nth-child(2) .text-sm').textContent.trim();
                
                // Save to localStorage
                savedItems[id] = {
                    name: productName,
                    price: productPrice,
                    image: productImage
                };
                
                localStorage.setItem('savedItems', JSON.stringify(savedItems));
                
                // Remove from cart
                row.querySelector('.cart-remove').click();
                
                // Show notification
                showNotification('Item saved for later');
                
                // Update saved items display
                renderSavedItems();
            });
        });
        
        // Render saved items from localStorage
        function renderSavedItems() {
            if (savedItemsContainer) {
                savedItemsContainer.innerHTML = '';
                
                const items = Object.entries(savedItems);
                
                if (items.length > 0) {
                    savedForLaterSection.classList.remove('hidden');
                    
                    items.forEach(([id, item]) => {
                        const itemElement = document.createElement('div');
                        itemElement.className = 'p-4 flex items-center';
                        itemElement.innerHTML = `
                            <div class="flex-shrink-0 w-16 h-16">
                                <img class="w-full h-full object-cover rounded" src="${item.image}" alt="${item.name}">
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-sm font-medium text-gray-900">${item.name}</h3>
                                <p class="mt-1 text-sm text-gray-500">${item.price}</p>
                            </div>
                            <div>
                                <button data-id="${id}" class="move-to-cart text-sm text-blue-600 hover:text-blue-800">Move to Cart</button>
                            </div>
                        `;
                        
                        savedItemsContainer.appendChild(itemElement);
                    });
                    
                    // Add event listeners to the "Move to Cart" buttons
                    document.querySelectorAll('.move-to-cart').forEach(button => {
                        button.addEventListener('click', function() {
                            const itemId = this.getAttribute('data-id');
                            
                            // Mock API call to add to cart
                            fetch('/cart/add', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ 
                                    id: itemId,
                                    quantity: 1
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Remove from saved items
                                    delete savedItems[itemId];
                                    localStorage.setItem('savedItems', JSON.stringify(savedItems));
                                    
                                    // Show notification
                                    showNotification('Item moved to cart');
                                    
                                    // Re-render saved items
                                    renderSavedItems();
                                    
                                    // Reload page to show updated cart
                                    window.location.reload();
                                }
                            })
                            .catch(error => {
                                console.error('Error moving item to cart:', error);
                            });
                        });
                    });
                } else {
                    savedForLaterSection.classList.add('hidden');
                }
            }
        }
        
        // Discount code functionality
        if (applyDiscountBtn && discountCode) {
            applyDiscountBtn.addEventListener('click', function() {
                const code = discountCode.value.trim();
                
                if (!code) {
                    showDiscountMessage('Please enter a discount code', 'error');
                    return;
                }
                
                // Disable button and show loading state
                applyDiscountBtn.disabled = true;
                applyDiscountBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Applying...';
                
                // Simulate API call for discount validation
                // In a real app, you would send this to your server
                setTimeout(() => {
                    // Reset button state
                    applyDiscountBtn.disabled = false;
                    applyDiscountBtn.textContent = 'Apply';
                    
                    // Demo discount codes
                    if (code.toUpperCase() === 'WELCOME10') {
                        // Calculate 10% discount
                        const subtotal = parseFloat(cartTotal.textContent.replace('$', ''));
                        const discount = (subtotal * 0.1).toFixed(2);
                        
                        // Show success message
                        showDiscountMessage('Discount applied: 10% off your order', 'success');
                        
                        // Update discount display
                        discountRow.classList.remove('hidden');
                        discountAmount.textContent = `-$${discount}`;
                        
                        // Update total
                        cartTotal.textContent = `$${(subtotal - discount).toFixed(2)}`;
                    } else if (code.toUpperCase() === 'FREESHIP') {
                        showDiscountMessage('Free shipping discount applied!', 'success');
                        shippingCost.textContent = '$0.00';
                    } else {
                        showDiscountMessage('Invalid discount code', 'error');
                    }
                }, 1000);
            });
        }
        
        function showDiscountMessage(message, type) {
            if (discountMessage) {
                discountMessage.textContent = message;
                discountMessage.classList.remove('hidden', 'text-red-600', 'text-green-600');
                
                if (type === 'error') {
                    discountMessage.classList.add('text-red-600');
                } else {
                    discountMessage.classList.add('text-green-600');
                }
            }
        }
        
        // Shipping calculation functionality
        if (calculateShippingBtn && shippingCountry && shippingPostal) {
            calculateShippingBtn.addEventListener('click', function() {
                const country = shippingCountry.value;
                const postal = shippingPostal.value.trim();
                
                if (!country) {
                    alert('Please select a country');
                    return;
                }
                
                if (!postal) {
                    alert('Please enter a postal/zip code');
                    return;
                }
                
                // Disable button and show loading state
                calculateShippingBtn.disabled = true;
                calculateShippingBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Calculating...';
                
                // Simulate API call for shipping calculation
                setTimeout(() => {
                    // Reset button state
                    calculateShippingBtn.disabled = false;
                    calculateShippingBtn.textContent = 'Calculate Shipping';
                    
                    // Demo shipping rates based on country
                    let shippingRate = 0;
                    
                    switch(country) {
                        case 'US':
                            shippingRate = 5.99;
                            break;
                        case 'CA':
                            shippingRate = 9.99;
                            break;
                        case 'MX':
                            shippingRate = 14.99;
                            break;
                        case 'UK':
                            shippingRate = 19.99;
                            break;
                        case 'AU':
                            shippingRate = 24.99;
                            break;
                        default:
                            shippingRate = 29.99;
                    }
                    
                    // Update shipping cost display
                    shippingCost.textContent = `$${shippingRate.toFixed(2)}`;
                    
                    // Calculate and update tax (mock calculation)
                    const subtotal = parseFloat(document.querySelector('dl div:first-child dd').textContent.replace('$', ''));
                    const taxRate = country === 'US' ? 0.07 : 0.1; // 7% for US, 10% for others
                    const calculatedTax = (subtotal * taxRate).toFixed(2);
                    
                    taxAmount.textContent = `$${calculatedTax}`;
                    
                    // Update total with shipping and tax
                    const discountVal = discountRow.classList.contains('hidden') ? 0 : 
                        parseFloat(discountAmount.textContent.replace('-$', ''));
                    
                    const totalWithAll = (subtotal + shippingRate + parseFloat(calculatedTax) - discountVal).toFixed(2);
                    cartTotal.textContent = `$${totalWithAll}`;
                    
                    // Show notification
                    showNotification('Shipping calculated successfully');
                }, 1500);
            });
        }
        
        // Show notification function
        function showNotification(message) {
            if (notification && notificationMessage) {
                notificationMessage.textContent = message;
                notification.classList.remove('translate-x-full');
                
                // Hide after 5 seconds
                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                }, 5000);
            }
        }
        
        // Close notification button
        if (closeNotificationBtn) {
            closeNotificationBtn.addEventListener('click', function() {
                notification.classList.add('translate-x-full');
            });
        }
        
        // Initialize saved items display
        renderSavedItems();
        
        // Initial cart total calculation
        updateCartTotals();
    });
</script>
@endpush
@endsection 