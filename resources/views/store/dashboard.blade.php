@extends('layouts.store')

@section('content')
<div class="container px-4 mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ tenant()->name }} Dashboard</h1>
        <p class="text-gray-600 mt-1">Manage your store and track performance</p>
    </div>
    
    <!-- Store Performance -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($totalRevenue ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3 mr-4">
                    <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalOrders ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="bg-indigo-100 rounded-full p-3 mr-4">
                    <i class="fas fa-box text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Products</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalProducts ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="bg-purple-100 rounded-full p-3 mr-4">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Customers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalCustomers ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Store Management Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Products Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Products</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $totalProducts ?? 0 }} total</span>
                </div>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">Manage your product catalog, add new items, and update inventory.</p>
                <div class="flex justify-between">
                    <a href="{{ route('store.products', [], false) }}" class="text-blue-600 hover:text-blue-800 font-medium">View all products</a>
                    <a href="{{ route('store.products.create', [], false) }}" class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm font-medium hover:bg-blue-700 transition">Add Product</a>
                </div>
            </div>
        </div>
        
        <!-- Orders Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-green-50 to-green-100 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Orders</h3>
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $totalOrders ?? 0 }} total</span>
                </div>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">Track and manage customer orders, process fulfillment, and generate invoices.</p>
                <div class="flex justify-between">
                    <a href="{{ route('store.orders', [], false) }}" class="text-green-600 hover:text-green-800 font-medium">View all orders</a>
                    <a href="{{ route('store.orders', [], false) }}" class="bg-green-600 text-white px-3 py-1 rounded-md text-sm font-medium hover:bg-green-700 transition">Process Orders</a>
                </div>
            </div>
        </div>
        
        <!-- Customers Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-purple-50 to-purple-100 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Customers</h3>
                    <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $totalCustomers ?? 0 }} total</span>
                </div>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">Manage customer accounts, view purchase history, and track loyalty.</p>
                <div class="flex justify-between">
                    <a href="{{ route('store.customers', [], false) }}" class="text-purple-600 hover:text-purple-800 font-medium">View all customers</a>
                    <a href="{{ route('store.customers.create', [], false) }}" class="bg-purple-600 text-white px-3 py-1 rounded-md text-sm font-medium hover:bg-purple-700 transition">Add Customer</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Configuration Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Theme Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-indigo-100 rounded-full p-2 mr-3">
                        <i class="fas fa-paint-brush text-indigo-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Theme</h3>
                </div>
                <p class="text-gray-600 mb-4">Customize your store's appearance, colors, and layout.</p>
                <a href="{{ route('store.themes', [], false) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Customize theme →</a>
            </div>
        </div>
        
        <!-- Settings Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-gray-100 rounded-full p-2 mr-3">
                        <i class="fas fa-cog text-gray-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
                </div>
                <p class="text-gray-600 mb-4">Configure store details, shipping, taxes, and policies.</p>
                <a href="{{ route('store.settings', [], false) }}" class="text-gray-600 hover:text-gray-800 font-medium">Manage settings →</a>
            </div>
        </div>
        
        <!-- Discount Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-yellow-100 rounded-full p-2 mr-3">
                        <i class="fas fa-tag text-yellow-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Discounts</h3>
                </div>
                <p class="text-gray-600 mb-4">Create promotional offers, discount codes, and sales.</p>
                <a href="{{ route('store.discounts', [], false) }}" class="text-yellow-600 hover:text-yellow-800 font-medium">Manage discounts →</a>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentOrders ?? [] as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->status_badge }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($order->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No recent orders</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                <a href="{{ route('store.orders', [], false) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View all orders →</a>
            </div>
        </div>
        
        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Products</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topProducts ?? [] as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($product->image)
                                            <img class="h-10 w-10 rounded-md object-cover" src="{{ $product->image }}" alt="{{ $product->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center text-gray-500">
                                                <i class="fas fa-box"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">SKU: {{ $product->sku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->stock > 10)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ $product->stock }} in stock</span>
                                @elseif($product->stock > 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $product->stock }} left</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Out of stock</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->order_count }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No products found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                <a href="{{ route('store.products', [], false) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View all products →</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // This script would initialize charts and other dashboard components
    document.addEventListener('DOMContentLoaded', function() {
        // Sales chart could be initialized here
        // const ctx = document.getElementById('salesChart').getContext('2d');
        // new Chart(ctx, { ... });
    });
</script>
@endpush 