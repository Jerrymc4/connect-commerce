@extends('layouts.admin.dashboard')

@section('content')
<div class="container px-4 mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">Store Orders</h1>
            <p class="text-secondary mt-1">Manage and process customer orders</p>
        </div>
        <div class="flex-shrink-0">
            <button type="button" class="inline-flex items-center justify-center rounded-md border border-border-color shadow-sm px-4 py-2 bg-card text-sm font-medium text-primary hover:bg-body focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                <i class="fas fa-file-export text-accent mr-2"></i>
                Export Orders
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-card rounded-lg p-6 shadow-sm border border-border-color hover:shadow-md transition-shadow duration-300 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 -mt-8 -mr-8 bg-blue-500 opacity-10 rounded-full"></div>
            <div class="flex items-center relative z-10">
                <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-3 mr-4">
                    <i class="fas fa-shopping-bag text-blue-600 dark:text-blue-300 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-secondary font-medium">Total Orders</p>
                    <p class="text-2xl font-bold text-primary">{{ $stats->total_orders ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-card rounded-lg p-6 shadow-sm border border-border-color hover:shadow-md transition-shadow duration-300 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 -mt-8 -mr-8 bg-orange-500 opacity-10 rounded-full"></div>
            <div class="flex items-center relative z-10">
                <div class="bg-orange-100 dark:bg-orange-900 rounded-full p-3 mr-4">
                    <i class="fas fa-clock text-orange-600 dark:text-orange-300 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-secondary font-medium">Pending Orders</p>
                    <p class="text-2xl font-bold text-primary">{{ $stats->pending_orders ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-card rounded-lg p-6 shadow-sm border border-border-color hover:shadow-md transition-shadow duration-300 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 -mt-8 -mr-8 bg-green-500 opacity-10 rounded-full"></div>
            <div class="flex items-center relative z-10">
                <div class="bg-green-100 dark:bg-green-900 rounded-full p-3 mr-4">
                    <i class="fas fa-dollar-sign text-green-600 dark:text-green-300 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-secondary font-medium">Today's Revenue</p>
                    <p class="text-2xl font-bold text-primary">${{ number_format($stats->today_revenue ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-card rounded-lg p-6 shadow-sm border border-border-color hover:shadow-md transition-shadow duration-300 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 -mt-8 -mr-8 bg-purple-500 opacity-10 rounded-full"></div>
            <div class="flex items-center relative z-10">
                <div class="bg-purple-100 dark:bg-purple-900 rounded-full p-3 mr-4">
                    <i class="fas fa-chart-line text-purple-600 dark:text-purple-300 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-secondary font-medium">Total Revenue</p>
                    <p class="text-2xl font-bold text-primary">${{ number_format($stats->total_revenue ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-card rounded-lg shadow-sm border border-border-color mb-6">
        <div class="p-4 border-b border-border-color">
            <h3 class="text-lg font-semibold text-primary">Filter Orders</h3>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="orderStatus" class="block text-sm font-medium text-secondary mb-1">Order Status</label>
                    <select id="orderStatus" name="orderStatus" class="w-full rounded-md border-border-color bg-body text-primary focus:border-accent focus:ring focus:ring-accent/20 focus:ring-opacity-50">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>

                <div>
                    <label for="dateRange" class="block text-sm font-medium text-secondary mb-1">Date Range</label>
                    <select id="dateRange" name="dateRange" class="w-full rounded-md border-border-color bg-body text-primary focus:border-accent focus:ring focus:ring-accent/20 focus:ring-opacity-50">
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="thisWeek">This Week</option>
                        <option value="lastWeek">Last Week</option>
                        <option value="thisMonth">This Month</option>
                        <option value="lastMonth">Last Month</option>
                        <option value="thisYear">This Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>

                <div>
                    <label for="searchQuery" class="block text-sm font-medium text-secondary mb-1">Search</label>
                    <div class="flex rounded-md">
                        <input type="text" name="searchQuery" id="searchQuery" class="flex-grow rounded-l-md border-r-0 border-border-color bg-body text-primary focus:border-accent focus:ring focus:ring-accent/20 focus:ring-opacity-50" placeholder="Order #, customer name, email...">
                        <button type="button" class="inline-flex items-center px-4 py-2 rounded-r-md border border-l-0 border-border-color bg-accent text-sm font-medium text-white hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-card rounded-lg shadow-sm border border-border-color mb-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border-color">
                <thead class="bg-body">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Order #</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Payment</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Status</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-card divide-y divide-border-color">
                    @forelse($orders ?? [] as $order)
                        <tr class="hover:bg-body transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">
                                #{{ $order->order_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">
                                {{ $order->customer_name ?? ($order->customer->name ?? 'N/A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">
                                {{ $order->created_at ? $order->created_at->format('M d, Y H:i') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">
                                ${{ number_format($order->total ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">
                                {{ $order->payment_method ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                    @if(($order->status ?? '') == 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @elseif(($order->status ?? '') == 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                    @elseif(($order->status ?? '') == 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                    @elseif(($order->status ?? '') == 'refunded') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                    @elseif(($order->status ?? '') == 'shipped') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                    @elseif(($order->status ?? '') == 'delivered') bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-300
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif">
                                    {{ ucfirst($order->status ?? 'pending') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="text-accent hover:text-accent/80 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.invoice', $order->id) }}" class="text-secondary hover:text-primary transition-colors">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    <button type="button" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition-colors">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="rounded-full bg-body p-3 mb-4">
                                        <i class="fas fa-shopping-cart text-secondary text-2xl"></i>
                                    </div>
                                    <p class="text-lg font-medium text-primary mb-1">No orders found</p>
                                    <p class="text-secondary text-sm">Adjust your filters or try again later</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if(isset($orders) && method_exists($orders, 'links'))
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-card rounded-lg shadow-sm border border-border-color">
            <div class="px-4 py-3 flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-border-color bg-card text-sm font-medium text-primary hover:bg-body">
                        <i class="fas fa-chevron-left mr-1"></i> Previous
                    </a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-border-color bg-card text-sm font-medium text-primary hover:bg-body">
                        Next <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-secondary">
                            Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">{{ isset($orders) && is_countable($orders) ? count($orders) : 0 }}</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-border-color bg-card text-sm font-medium text-primary hover:bg-body">
                                <span class="sr-only">Previous</span>
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <a href="#" aria-current="page" class="relative inline-flex items-center px-4 py-2 border border-border-color bg-accent text-sm font-medium text-white">
                                1
                            </a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-border-color bg-card text-sm font-medium text-primary hover:bg-body">
                                2
                            </a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-border-color bg-card text-sm font-medium text-primary hover:bg-body hidden md:inline-flex">
                                3
                            </a>
                            <span class="relative inline-flex items-center px-4 py-2 border border-border-color bg-card text-sm font-medium text-secondary">
                                ...
                            </span>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-border-color bg-card text-sm font-medium text-primary hover:bg-body hidden md:inline-flex">
                                8
                            </a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-border-color bg-card text-sm font-medium text-primary hover:bg-body">
                                9
                            </a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-border-color bg-card text-sm font-medium text-primary hover:bg-body">
                                10
                            </a>
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-border-color bg-card text-sm font-medium text-primary hover:bg-body">
                                <span class="sr-only">Next</span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 