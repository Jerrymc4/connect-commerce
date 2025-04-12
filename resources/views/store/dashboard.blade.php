@extends('layouts.store')

@section('content')
<div class="container px-4 mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">{{ tenant()->name }} Dashboard</h1>
            <p class="text-secondary mt-1">Manage your store and track performance</p>
        </div>
        
        <!-- Quick Actions Menu -->
        <div class="flex-shrink-0">
            <div class="relative inline-block text-left" x-data="{ open: false }">
                <div>
                    <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-border-color shadow-sm px-4 py-2 bg-card text-sm font-medium text-primary hover:bg-body focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <i class="fas fa-bolt text-accent mr-2"></i>
                        Quick Actions
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-card ring-1 ring-border-color ring-opacity-5 z-10" style="display: none;">
                    <div class="py-1">
                        <a href="{{ route('store.products.create', [], false) }}" class="block px-4 py-2 text-sm text-primary hover:bg-body hover:text-accent transition-colors">
                            <i class="fas fa-plus text-blue-500 dark:text-blue-400 mr-2"></i> Add New Product
                        </a>
                        <a href="{{ route('store.orders', [], false) }}" class="block px-4 py-2 text-sm text-primary hover:bg-body hover:text-accent transition-colors">
                            <i class="fas fa-shipping-fast text-green-500 dark:text-green-400 mr-2"></i> Process Orders
                        </a>
                        <a href="{{ route('store.discounts.create', [], false) }}" class="block px-4 py-2 text-sm text-primary hover:bg-body hover:text-accent transition-colors">
                            <i class="fas fa-tag text-orange-500 dark:text-orange-400 mr-2"></i> Create Discount
                        </a>
                        <a href="{{ route('store.themes', [], false) }}" class="block px-4 py-2 text-sm text-primary hover:bg-body hover:text-accent transition-colors">
                            <i class="fas fa-paint-brush text-purple-500 dark:text-purple-400 mr-2"></i> Customize Theme
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Store Setup Progress (for new stores) -->
    @if(($totalProducts ?? 0) < 5 || ($totalOrders ?? 0) < 1)
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300">Complete your store setup</h3>
                <div class="mt-2 text-blue-700 dark:text-blue-400">
                    <div class="flex flex-col space-y-4">
                        <div class="flex items-center">
                            <div class="w-full bg-blue-200 dark:bg-blue-800 rounded-full h-2.5">
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
                                <div class="bg-blue-600 dark:bg-blue-400 h-2.5 rounded-full" style="width: {{ $setupProgress }}%"></div>
                            </div>
                            <span class="ml-3 text-sm font-medium text-blue-800 dark:text-blue-300">{{ $setupProgress }}%</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <a href="{{ route('store.products.create', [], false) }}" class="flex items-center text-blue-700 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                <span class="flex-shrink-0 h-5 w-5 {{ ($totalProducts ?? 0) > 0 ? 'text-green-500 dark:text-green-400' : 'text-blue-500 dark:text-blue-400' }}">
                                    @if(($totalProducts ?? 0) > 0)
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="far fa-circle"></i>
                                    @endif
                                </span>
                                <span class="ml-1.5">{{ ($totalProducts ?? 0) > 0 ? 'Add more products' : 'Add your first product' }}</span>
                            </a>
                            <a href="{{ route('store.themes', [], false) }}" class="flex items-center text-blue-700 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                <span class="flex-shrink-0 h-5 w-5 {{ isset(tenant()->data['theme']) ? 'text-green-500 dark:text-green-400' : 'text-blue-500 dark:text-blue-400' }}">
                                    @if(isset(tenant()->data['theme']))
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="far fa-circle"></i>
                                    @endif
                                </span>
                                <span class="ml-1.5">Customize your store theme</span>
                            </a>
                            <a href="{{ route('store.settings', [], false) }}" class="flex items-center text-blue-700 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                <span class="flex-shrink-0 h-5 w-5 {{ isset(tenant()->data['settings']) ? 'text-green-500 dark:text-green-400' : 'text-blue-500 dark:text-blue-400' }}">
                                    @if(isset(tenant()->data['settings']))
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="far fa-circle"></i>
                                    @endif
                                </span>
                                <span class="ml-1.5">Configure store settings</span>
                            </a>
                            <a href="/" target="_blank" class="flex items-center text-blue-700 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                <span class="flex-shrink-0 h-5 w-5 text-blue-500 dark:text-blue-400">
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
    <div class="bg-card rounded-lg shadow-sm border border-border-color mb-8">
        <div class="p-4 border-b border-border-color flex items-center justify-between">
            <h3 class="text-lg font-semibold text-primary">Notifications</h3>
            <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">View all</a>
        </div>
        <div class="divide-y divide-border-color">
            @foreach($notifications->take(3) as $notification)
            <div class="p-4 hover:bg-body transition-colors">
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        @if($notification->type == 'order')
                            <div class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center text-green-500 dark:text-green-300">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        @elseif($notification->type == 'customer')
                            <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-500 dark:text-blue-300">
                                <i class="fas fa-user"></i>
                            </div>
                        @elseif($notification->type == 'alert')
                            <div class="h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center text-red-500 dark:text-red-300">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        @else
                            <div class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-300">
                                <i class="fas fa-bell"></i>
                            </div>
                        @endif
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-primary">{{ $notification->title }}</p>
                        <p class="mt-1 text-sm text-secondary">{{ $notification->message }}</p>
                        <div class="mt-2 text-xs text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Store Performance -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-card rounded-lg p-6 shadow-sm border border-border-color hover:shadow-md transition-shadow duration-300 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 -mt-8 -mr-8 bg-blue-500 opacity-10 rounded-full"></div>
            <div class="flex items-center relative z-10">
                <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-3 mr-4">
                    <i class="fas fa-dollar-sign text-blue-600 dark:text-blue-300 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-secondary font-medium">Total Revenue</p>
                    <p class="text-2xl font-bold text-primary">${{ number_format($totalRevenue ?? 0, 2) }}</p>
                </div>
            </div>
            @if(isset($revenueGrowth))
                <div class="mt-2 flex items-center text-xs relative z-10">
                    @if($revenueGrowth > 0)
                        <span class="text-green-600 dark:text-green-400 flex items-center"><i class="fas fa-arrow-up mr-1"></i> {{ number_format($revenueGrowth, 1) }}%</span>
                    @elseif($revenueGrowth < 0)
                        <span class="text-red-600 dark:text-red-400 flex items-center"><i class="fas fa-arrow-down mr-1"></i> {{ number_format(abs($revenueGrowth), 1) }}%</span>
                    @else
                        <span class="text-gray-600 dark:text-gray-400 flex items-center"><i class="fas fa-minus mr-1"></i> 0%</span>
                    @endif
                    <span class="ml-1 text-muted">vs. last period</span>
                </div>
            @endif
        </div>
        
        <div class="bg-card rounded-lg p-6 shadow-sm border border-border-color hover:shadow-md transition-shadow duration-300 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 -mt-8 -mr-8 bg-green-500 opacity-10 rounded-full"></div>
            <div class="flex items-center relative z-10">
                <div class="bg-green-100 dark:bg-green-900 rounded-full p-3 mr-4">
                    <i class="fas fa-shopping-cart text-green-600 dark:text-green-300 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-secondary font-medium">Total Orders</p>
                    <p class="text-2xl font-bold text-primary">{{ $totalOrders ?? 0 }}</p>
                </div>
            </div>
            @if(isset($ordersGrowth))
                <div class="mt-2 flex items-center text-xs relative z-10">
                    @if($ordersGrowth > 0)
                        <span class="text-green-600 dark:text-green-400 flex items-center"><i class="fas fa-arrow-up mr-1"></i> {{ number_format($ordersGrowth, 1) }}%</span>
                    @elseif($ordersGrowth < 0)
                        <span class="text-red-600 dark:text-red-400 flex items-center"><i class="fas fa-arrow-down mr-1"></i> {{ number_format(abs($ordersGrowth), 1) }}%</span>
                    @else
                        <span class="text-gray-600 dark:text-gray-400 flex items-center"><i class="fas fa-minus mr-1"></i> 0%</span>
                    @endif
                    <span class="ml-1 text-muted">vs. last period</span>
                </div>
            @endif
        </div>
        
        <div class="bg-card rounded-lg p-6 shadow-sm border border-border-color hover:shadow-md transition-shadow duration-300 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 -mt-8 -mr-8 bg-indigo-500 opacity-10 rounded-full"></div>
            <div class="flex items-center relative z-10">
                <div class="bg-indigo-100 dark:bg-indigo-900 rounded-full p-3 mr-4">
                    <i class="fas fa-box text-indigo-600 dark:text-indigo-300 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-secondary font-medium">Products</p>
                    <p class="text-2xl font-bold text-primary">{{ $totalProducts ?? 0 }}</p>
                </div>
            </div>
            @if(isset($lowStockProducts) && $lowStockProducts > 0)
                <div class="mt-2 flex items-center text-xs relative z-10">
                    <span class="text-orange-600 dark:text-orange-400 flex items-center"><i class="fas fa-exclamation-triangle mr-1"></i> {{ $lowStockProducts }} low stock</span>
                </div>
            @endif
        </div>
        
        <div class="bg-card rounded-lg p-6 shadow-sm border border-border-color hover:shadow-md transition-shadow duration-300 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 -mt-8 -mr-8 bg-purple-500 opacity-10 rounded-full"></div>
            <div class="flex items-center relative z-10">
                <div class="bg-purple-100 dark:bg-purple-900 rounded-full p-3 mr-4">
                    <i class="fas fa-users text-purple-600 dark:text-purple-300 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-secondary font-medium">Customers</p>
                    <p class="text-2xl font-bold text-primary">{{ $totalCustomers ?? 0 }}</p>
                </div>
            </div>
            @if(isset($newCustomers) && $newCustomers > 0)
                <div class="mt-2 flex items-center text-xs relative z-10">
                    <span class="text-green-600 dark:text-green-400 flex items-center"><i class="fas fa-user-plus mr-1"></i> {{ $newCustomers }} new this week</span>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Sales Chart -->
        <div class="bg-card rounded-lg shadow-sm border border-border-color hover:shadow-md transition-shadow duration-300">
            <div class="p-6 border-b border-border-color bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-primary">Sales Overview</h3>
                    <div class="flex items-center">
                        <select id="salesTimeRange" class="bg-input border border-border-color text-primary text-sm rounded-lg focus:ring-primary focus:border-primary block p-1.5 pl-3 pr-10">
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
        <div class="bg-card rounded-lg shadow-sm border border-border-color hover:shadow-md transition-shadow duration-300">
            <div class="p-6 border-b border-border-color bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-primary">Traffic & Conversion</h3>
                    <div class="flex items-center">
                        <select id="trafficTimeRange" class="bg-input border border-border-color text-primary text-sm rounded-lg focus:ring-primary focus:border-primary block p-1.5 pl-3 pr-10">
                            <option value="7">Last 7 days</option>
                            <option value="30" selected>Last 30 days</option>
                            <option value="90">Last 90 days</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-body rounded-lg p-4 text-center">
                        <p class="text-sm text-secondary font-medium">Visitors</p>
                        <p class="text-xl font-bold text-primary">{{ number_format($totalVisitors ?? 0) }}</p>
                    </div>
                    <div class="bg-body rounded-lg p-4 text-center">
                        <p class="text-sm text-secondary font-medium">Conversion Rate</p>
                        <p class="text-xl font-bold text-accent">{{ number_format($conversionRate ?? 0, 1) }}%</p>
                    </div>
                </div>
                <canvas id="trafficChart" height="240"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Store Management Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

    </div>
    
    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-card rounded-lg shadow-sm border border-border-color overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-6 border-b border-border-color bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20">
                <h3 class="text-lg font-semibold text-primary">Recent Orders</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-border-color">
                    <thead class="bg-body">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Order</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-card divide-y divide-border-color">
                        @forelse($recentOrders ?? [] as $order)
                        <tr class="hover:bg-body transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">#{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->status_badge }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">${{ number_format($order->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-secondary">No recent orders</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-border-color bg-body">
                <a href="{{ route('store.orders', [], false) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View all orders →</a>
            </div>
        </div>
        
        <!-- Top Products -->
        <div class="bg-card rounded-lg shadow-sm border border-border-color overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="p-6 border-b border-border-color bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20">
                <h3 class="text-lg font-semibold text-primary">Top Products</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-border-color">
                    <thead class="bg-body">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Price</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Stock</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Orders</th>
                        </tr>
                    </thead>
                    <tbody class="bg-card divide-y divide-border-color">
                        @forelse($topProducts ?? [] as $product)
                        <tr class="hover:bg-body transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($product->image)
                                            <img class="h-10 w-10 rounded-md object-cover" src="{{ $product->image }}" alt="{{ $product->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-md bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-300">
                                                <i class="fas fa-box"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-primary">{{ $product->name }}</div>
                                        <div class="text-sm text-secondary">SKU: {{ $product->sku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->stock > 10)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">{{ $product->stock }} in stock</span>
                                @elseif($product->stock > 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">{{ $product->stock }} left</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">Out of stock</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">{{ $product->order_count }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-secondary">No products found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-border-color bg-body">
                <a href="{{ route('store.products', [], false) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View all products →</a>
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
        // Configure chart colors based on theme
        function getChartColors() {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                blue: {
                    primary: isDark ? 'rgba(96, 165, 250, 1)' : 'rgba(59, 130, 246, 1)',
                    secondary: isDark ? 'rgba(96, 165, 250, 0.1)' : 'rgba(59, 130, 246, 0.1)',
                },
                green: {
                    primary: isDark ? 'rgba(52, 211, 153, 1)' : 'rgba(16, 185, 129, 1)',
                    secondary: isDark ? 'rgba(52, 211, 153, 0.1)' : 'rgba(16, 185, 129, 0.1)',
                },
                purple: {
                    primary: isDark ? 'rgba(167, 139, 250, 1)' : 'rgba(139, 92, 246, 1)',
                    secondary: isDark ? 'rgba(167, 139, 250, 0.1)' : 'rgba(139, 92, 246, 0.1)',
                },
                text: isDark ? '#e5e7eb' : '#111827',
                grid: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
            };
        }

        let colors = getChartColors();
        
        // Sales Chart
        const salesChartCtx = document.getElementById('salesChart').getContext('2d');
        
        // Sample data - in a real app, this would come from the backend
        const salesData = {
            labels: {!! json_encode($salesData->pluck('date')->toArray() ?? []) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($salesData->pluck('total')->toArray() ?? []) !!},
                backgroundColor: colors.blue.secondary,
                borderColor: colors.blue.primary,
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
                            },
                            color: colors.text
                        },
                        grid: {
                            color: colors.grid
                        }
                    },
                    x: {
                        ticks: {
                            color: colors.text
                        },
                        grid: {
                            color: colors.grid
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
                backgroundColor: colors.purple.secondary,
                borderColor: colors.purple.primary,
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }, {
                label: 'Orders',
                data: [15, 25, 20, 30, 40, 35, 25],
                backgroundColor: colors.green.secondary,
                borderColor: colors.green.primary,
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
                        labels: {
                            color: colors.text
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: colors.text
                        },
                        grid: {
                            color: colors.grid
                        }
                    },
                    x: {
                        ticks: {
                            color: colors.text
                        },
                        grid: {
                            color: colors.grid
                        }
                    }
                }
            }
        });
        
        // Update charts when theme changes
        document.addEventListener('alpine:initialized', () => {
            Alpine.effect(() => {
                const darkMode = Alpine.store('darkMode');
                if (darkMode !== undefined) {
                    // Color update on theme change
                    setTimeout(() => {
                        colors = getChartColors();
                        
                        // Update sales chart
                        salesChart.data.datasets[0].backgroundColor = colors.blue.secondary;
                        salesChart.data.datasets[0].borderColor = colors.blue.primary;
                        salesChart.options.scales.y.ticks.color = colors.text;
                        salesChart.options.scales.x.ticks.color = colors.text;
                        salesChart.options.scales.y.grid.color = colors.grid;
                        salesChart.options.scales.x.grid.color = colors.grid;
                        salesChart.update();
                        
                        // Update traffic chart
                        trafficChart.data.datasets[0].backgroundColor = colors.purple.secondary;
                        trafficChart.data.datasets[0].borderColor = colors.purple.primary;
                        trafficChart.data.datasets[1].backgroundColor = colors.green.secondary;
                        trafficChart.data.datasets[1].borderColor = colors.green.primary;
                        trafficChart.options.scales.y.ticks.color = colors.text;
                        trafficChart.options.scales.x.ticks.color = colors.text;
                        trafficChart.options.scales.y.grid.color = colors.grid;
                        trafficChart.options.scales.x.grid.color = colors.grid;
                        trafficChart.options.plugins.legend.labels.color = colors.text;
                        trafficChart.update();
                    }, 100);
                }
            });
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