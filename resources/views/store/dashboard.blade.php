@extends('layouts.store')

@section('content')
<div class="container px-4 mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ tenant()->name }} Dashboard</h1>
            <p class="text-gray-600 mt-1">Manage your store and track performance</p>
        </div>
        
        <!-- Quick Actions Menu -->
        <div class="flex-shrink-0">
            <div class="relative inline-block text-left" x-data="{ open: false }">
                <div>
                    <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Quick Actions
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10" style="display: none;">
                    <div class="py-1">
                        <a href="{{ route('store.products.create', [], false) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-plus text-blue-500 mr-2"></i> Add New Product
                        </a>
                        <a href="{{ route('store.orders', [], false) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-shipping-fast text-green-500 mr-2"></i> Process Orders
                        </a>
                        <a href="{{ route('store.discounts.create', [], false) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-tag text-orange-500 mr-2"></i> Create Discount
                        </a>
                        <a href="{{ route('store.themes', [], false) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-paint-brush text-purple-500 mr-2"></i> Customize Theme
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Store Setup Progress (for new stores) -->
    @if(($totalProducts ?? 0) < 5 || ($totalOrders ?? 0) < 1)
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-blue-800">Complete your store setup</h3>
                <div class="mt-2 text-blue-700">
                    <div class="flex flex-col space-y-4">
                        <div class="flex items-center">
                            <div class="w-full bg-blue-200 rounded-full h-2.5">
                                @php
                                    $setupProgress = 0;
                                    // Add 30% if they have at least one product
                                    if(($totalProducts ?? 0) > 0) $setupProgress += 30;
                                    // Add another 20% if they have 5+ products
                                    if(($totalProducts ?? 0) >= 5) $setupProgress += 20;
                                    // Add 20% if they customized theme
                                    if(isset(tenant()->data['theme'])) $setupProgress += 20;
                                    // Add 20% if they configured settings
                                    if(isset(tenant()->data['settings'])) $setupProgress += 20;
                                    // Add 10% if they have at least one order
                                    if(($totalOrders ?? 0) > 0) $setupProgress += 10;
                                @endphp
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $setupProgress }}%"></div>
                            </div>
                            <span class="ml-3 text-sm font-medium text-blue-800">{{ $setupProgress }}%</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <a href="{{ route('store.products.create', [], false) }}" class="flex items-center text-blue-700 hover:text-blue-900">
                                <span class="flex-shrink-0 h-5 w-5 {{ ($totalProducts ?? 0) > 0 ? 'text-green-500' : 'text-blue-500' }}">
                                    @if(($totalProducts ?? 0) > 0)
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="far fa-circle"></i>
                                    @endif
                                </span>
                                <span class="ml-1.5">{{ ($totalProducts ?? 0) > 0 ? 'Add more products' : 'Add your first product' }}</span>
                            </a>
                            <a href="{{ route('store.themes', [], false) }}" class="flex items-center text-blue-700 hover:text-blue-900">
                                <span class="flex-shrink-0 h-5 w-5 {{ isset(tenant()->data['theme']) ? 'text-green-500' : 'text-blue-500' }}">
                                    @if(isset(tenant()->data['theme']))
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="far fa-circle"></i>
                                    @endif
                                </span>
                                <span class="ml-1.5">Customize your store theme</span>
                            </a>
                            <a href="{{ route('store.settings', [], false) }}" class="flex items-center text-blue-700 hover:text-blue-900">
                                <span class="flex-shrink-0 h-5 w-5 {{ isset(tenant()->data['settings']) ? 'text-green-500' : 'text-blue-500' }}">
                                    @if(isset(tenant()->data['settings']))
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="far fa-circle"></i>
                                    @endif
                                </span>
                                <span class="ml-1.5">Configure store settings</span>
                            </a>
                            <a href="/" target="_blank" class="flex items-center text-blue-700 hover:text-blue-900">
                                <span class="flex-shrink-0 h-5 w-5 text-blue-500">
                                    <i class="far fa-circle"></i>
                                </span>
                                <span class="ml-1.5">Preview your store</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Notifications -->
    @if(isset($notifications) && count($notifications) > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
            <a href="#" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($notifications->take(3) as $notification)
            <div class="p-4 hover:bg-gray-50">
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        @if($notification->type == 'order')
                            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-500">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        @elseif($notification->type == 'customer')
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                                <i class="fas fa-user"></i>
                            </div>
                        @elseif($notification->type == 'alert')
                            <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-500">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        @else
                            <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                                <i class="fas fa-bell"></i>
                            </div>
                        @endif
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                        <p class="mt-1 text-sm text-gray-500">{{ $notification->message }}</p>
                        <div class="mt-2 text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
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
            @if(isset($revenueGrowth))
                <div class="mt-2 flex items-center text-xs">
                    @if($revenueGrowth > 0)
                        <span class="text-green-600 flex items-center"><i class="fas fa-arrow-up mr-1"></i> {{ number_format($revenueGrowth, 1) }}%</span>
                    @elseif($revenueGrowth < 0)
                        <span class="text-red-600 flex items-center"><i class="fas fa-arrow-down mr-1"></i> {{ number_format(abs($revenueGrowth), 1) }}%</span>
                    @else
                        <span class="text-gray-600 flex items-center"><i class="fas fa-minus mr-1"></i> 0%</span>
                    @endif
                    <span class="ml-1 text-gray-500">vs. last period</span>
                </div>
            @endif
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
            @if(isset($ordersGrowth))
                <div class="mt-2 flex items-center text-xs">
                    @if($ordersGrowth > 0)
                        <span class="text-green-600 flex items-center"><i class="fas fa-arrow-up mr-1"></i> {{ number_format($ordersGrowth, 1) }}%</span>
                    @elseif($ordersGrowth < 0)
                        <span class="text-red-600 flex items-center"><i class="fas fa-arrow-down mr-1"></i> {{ number_format(abs($ordersGrowth), 1) }}%</span>
                    @else
                        <span class="text-gray-600 flex items-center"><i class="fas fa-minus mr-1"></i> 0%</span>
                    @endif
                    <span class="ml-1 text-gray-500">vs. last period</span>
                </div>
            @endif
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
            @if(isset($lowStockProducts) && $lowStockProducts > 0)
                <div class="mt-2 flex items-center text-xs">
                    <span class="text-orange-600 flex items-center"><i class="fas fa-exclamation-triangle mr-1"></i> {{ $lowStockProducts }} low stock</span>
                </div>
            @endif
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
            @if(isset($newCustomers) && $newCustomers > 0)
                <div class="mt-2 flex items-center text-xs">
                    <span class="text-green-600 flex items-center"><i class="fas fa-user-plus mr-1"></i> {{ $newCustomers }} new this week</span>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Sales Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Sales Overview</h3>
                    <div class="flex items-center">
                        <select id="salesTimeRange" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-1.5 pl-3 pr-10">
                            <option value="7">Last 7 days</option>
                            <option value="30" selected>Last 30 days</option>
                            <option value="90">Last 90 days</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <canvas id="salesChart" height="300"></canvas>
            </div>
        </div>
        
        <!-- Traffic & Conversion -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Traffic & Conversion</h3>
                    <div class="flex items-center">
                        <select id="trafficTimeRange" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-1.5 pl-3 pr-10">
                            <option value="7">Last 7 days</option>
                            <option value="30" selected>Last 30 days</option>
                            <option value="90">Last 90 days</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <p class="text-sm text-gray-500 font-medium">Visitors</p>
                        <p class="text-xl font-bold text-gray-900">{{ number_format($totalVisitors ?? 0) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <p class="text-sm text-gray-500 font-medium">Conversion Rate</p>
                        <p class="text-xl font-bold text-gray-900">{{ number_format($conversionRate ?? 0, 1) }}%</p>
                    </div>
                </div>
                <canvas id="trafficChart" height="240"></canvas>
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
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart
        const salesChartCtx = document.getElementById('salesChart').getContext('2d');
        
        // Sample data - in a real app, this would come from the backend
        const salesData = {
            labels: {!! json_encode($salesData->pluck('date')->toArray() ?? []) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($salesData->pluck('total')->toArray() ?? []) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        };
        
        const salesChart = new Chart(salesChartCtx, {
            type: 'line',
            data: salesData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$ ' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });
        
        // Traffic Chart
        const trafficChartCtx = document.getElementById('trafficChart').getContext('2d');
        
        // Sample data for traffic - in a real app, this would come from the backend
        const trafficData = {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Visitors',
                data: [120, 190, 170, 250, 300, 280, 220],
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }, {
                label: 'Orders',
                data: [15, 25, 20, 30, 40, 35, 25],
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        };
        
        const trafficChart = new Chart(trafficChartCtx, {
            type: 'line',
            data: trafficData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Handle time range changes
        document.getElementById('salesTimeRange').addEventListener('change', function(e) {
            // In a real app, this would fetch new data based on the selected time range
            alert('This would fetch sales data for the last ' + e.target.value + ' days');
        });
        
        document.getElementById('trafficTimeRange').addEventListener('change', function(e) {
            // In a real app, this would fetch new data based on the selected time range
            alert('This would fetch traffic data for the last ' + e.target.value + ' days');
        });
    });
</script>
@endpush 