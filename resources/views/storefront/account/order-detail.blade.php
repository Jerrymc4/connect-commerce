@extends('layouts.storefront')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Account sidebar -->
        <div class="w-full md:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">My Account</h2>
                <nav class="space-y-1">
                    <a href="{{ route('customer.account') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('customer.orders') }}" class="block py-2 px-3 bg-blue-50 text-blue-700 rounded-md font-medium">
                        <i class="fas fa-shopping-bag mr-2"></i> Orders
                    </a>
                    <a href="{{ route('customer.addresses') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-map-marker-alt mr-2"></i> Addresses
                    </a>
                    <a href="{{ route('customer.payment-methods') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-credit-card mr-2"></i> Payment Methods
                    </a>
                    <a href="{{ route('customer.profile') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-user mr-2"></i> Account Details
                    </a>
                    <a href="{{ route('customer.password') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-lock mr-2"></i> Change Password
                    </a>
                    <a href="{{ route('customer.wishlist') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-heart mr-2"></i> Wishlist
                    </a>
                    <form method="POST" action="{{ route('customer.logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </nav>
            </div>
        </div>
        
        <!-- Account content -->
        <div class="w-full md:w-3/4">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Order header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-wrap items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold">Order #{{ $order->order_number }}</h1>
                            <p class="text-gray-600 mt-1">Placed on {{ $order->created_at->format('F j, Y') }}</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            @if($order->status == 'processing')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Processing
                                </span>
                            @elseif($order->status == 'shipped')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Shipped
                                </span>
                            @elseif($order->status == 'delivered')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Delivered
                                </span>
                            @elseif($order->status == 'cancelled')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Cancelled
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Order items -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold mb-4">Order Items</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($item->product && $item->product->image)
                                                    <img class="h-10 w-10 rounded-md object-cover" src="{{ $item->product->image }}" alt="{{ $item->product_name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center text-gray-400">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    @if($item->product)
                                                        <a href="{{ route('storefront.products.show', $item->product->slug) }}" class="hover:text-blue-600">
                                                            {{ $item->product_name }}
                                                        </a>
                                                    @else
                                                        {{ $item->product_name }}
                                                    @endif
                                                </div>
                                                @if($item->options)
                                                    <div class="text-xs text-gray-500">
                                                        @foreach(json_decode($item->options, true) ?? [] as $key => $value)
                                                            <span>{{ ucfirst($key) }}: {{ is_array($value) ? implode(', ', $value) : $value }}</span><br>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">${{ number_format($item->price, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Order summary -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row">
                        <!-- Customer info -->
                        <div class="w-full md:w-1/2 mb-6 md:mb-0">
                            <h2 class="text-lg font-semibold mb-4">Customer Information</h2>
                            
                            <div class="bg-gray-50 rounded-md p-4">
                                <h3 class="font-medium text-gray-700 mb-2">Contact Information</h3>
                                <p class="text-gray-600">{{ $order->customer_name }}</p>
                                <p class="text-gray-600">{{ $order->customer_email }}</p>
                                @if($order->customer_phone)
                                    <p class="text-gray-600">{{ $order->customer_phone }}</p>
                                @endif
                                
                                <h3 class="font-medium text-gray-700 mt-4 mb-2">Shipping Address</h3>
                                <p class="text-gray-600">{{ $order->shipping_address }}</p>
                                <p class="text-gray-600">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                                <p class="text-gray-600">{{ $order->shipping_country }}</p>
                                
                                <h3 class="font-medium text-gray-700 mt-4 mb-2">Billing Address</h3>
                                <p class="text-gray-600">{{ $order->billing_address }}</p>
                                <p class="text-gray-600">{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_zip }}</p>
                                <p class="text-gray-600">{{ $order->billing_country }}</p>
                            </div>
                        </div>
                        
                        <!-- Order totals -->
                        <div class="w-full md:w-1/2 md:pl-6">
                            <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                            
                            <div class="bg-gray-50 rounded-md p-4">
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="text-gray-900">${{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                
                                @if($order->discount_amount > 0)
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Discount</span>
                                    <span class="text-green-600">-${{ number_format($order->discount_amount, 2) }}</span>
                                </div>
                                @endif
                                
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="text-gray-900">${{ number_format($order->shipping_amount, 2) }}</span>
                                </div>
                                
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Tax</span>
                                    <span class="text-gray-900">${{ number_format($order->tax_amount, 2) }}</span>
                                </div>
                                
                                <div class="flex justify-between py-2 font-semibold border-t border-gray-200 mt-2 pt-2">
                                    <span class="text-gray-900">Total</span>
                                    <span class="text-gray-900">${{ number_format($order->total, 2) }}</span>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <h3 class="font-medium text-gray-700 mb-2">Payment Information</h3>
                                    <p class="text-gray-600">
                                        <span class="font-medium">Method:</span> 
                                        {{ $order->payment_method }}
                                    </p>
                                    <p class="text-gray-600">
                                        <span class="font-medium">Status:</span>
                                        @if($order->payment_status == 'paid')
                                            <span class="text-green-600">Paid</span>
                                        @elseif($order->payment_status == 'pending')
                                            <span class="text-yellow-600">Pending</span>
                                        @else
                                            <span class="text-red-600">{{ ucfirst($order->payment_status) }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order history -->
                @if($order->history && count($order->history) > 0)
                <div class="p-6">
                    <h2 class="text-lg font-semibold mb-4">Order History</h2>
                    
                    <div class="space-y-4">
                        @foreach($order->history as $history)
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600">
                                    <i class="fas fa-circle-notch"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900">{{ $history->status }}</h3>
                                <p class="text-sm text-gray-500">{{ $history->created_at->format('F j, Y g:i A') }}</p>
                                @if($history->comment)
                                    <p class="text-sm text-gray-600 mt-1">{{ $history->comment }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <div class="p-6 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('customer.orders') }}" class="text-blue-600 hover:underline flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Orders
                        </a>
                        
                        @if($order->status != 'cancelled' && $order->status != 'delivered')
                        <a href="#" class="text-gray-600 hover:text-gray-900 flex items-center">
                            <i class="fas fa-envelope mr-2"></i> Contact Support
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 