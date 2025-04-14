@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Orders</h1>
        <div class="flex space-x-2">
            <button class="bg-gray-800 border border-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition flex items-center">
                <i class="fas fa-file-export mr-2"></i> Export
            </button>
            <button class="bg-gray-800 border border-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition flex items-center">
                <i class="fas fa-print mr-2"></i> Print
            </button>
        </div>
    </div>
    
    <!-- Filters and Search -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="relative max-w-xs w-full">
            <input type="text" name="search" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Search orders...">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
        </div>
        
        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 w-full md:w-auto">
            <select class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
                <option value="refunded">Refunded</option>
            </select>
            
            <select class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                <option value="all">All Payment Methods</option>
                <option value="credit_card">Credit Card</option>
                <option value="paypal">PayPal</option>
                <option value="stripe">Stripe</option>
            </select>
            
            <div class="relative">
                <input type="text" name="date_range" class="border border-gray-300 rounded-md px-3 py-2 w-full md:w-auto focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Date range">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="fas fa-calendar text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-gray-100 rounded-lg p-4 border border-gray-200 text-center shadow-md">
            <p class="text-sm font-medium text-gray-600">All Orders</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ $totalOrders ?? 0 }}</p>
        </div>
        <div class="bg-gray-100 rounded-lg p-4 border border-gray-200 text-center shadow-md">
            <p class="text-sm font-medium text-gray-600">Pending</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ $pendingOrders ?? 0 }}</p>
        </div>
        <div class="bg-blue-100 rounded-lg p-4 border border-blue-200 text-center shadow-md">
            <p class="text-sm font-medium text-blue-600">Processing</p>
            <p class="text-xl font-bold text-blue-800 mt-1">{{ $processingOrders ?? 0 }}</p>
        </div>
        <div class="bg-blue-100 rounded-lg p-4 border border-blue-200 text-center shadow-md">
            <p class="text-sm font-medium text-blue-600">Completed</p>
            <p class="text-xl font-bold text-blue-800 mt-1">{{ $completedOrders ?? 0 }}</p>
        </div>
        <div class="bg-gray-100 rounded-lg p-4 border border-gray-200 text-center shadow-md">
            <p class="text-sm font-medium text-gray-600">Cancelled</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ $cancelledOrders ?? 0 }}</p>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-800">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Order ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Customer</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Payment</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if(isset($orders) && count($orders) > 0)
                    @foreach($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                                $order->status == 'completed' ? 'bg-blue-100 text-blue-800' : 
                                ($order->status == 'processing' ? 'bg-blue-100 text-blue-800' : 
                                ($order->status == 'cancelled' ? 'bg-gray-100 text-gray-800' : 
                                'bg-gray-100 text-gray-800')) 
                            }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ number_format($order->total, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.orders.view', $order->id) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.orders.invoice', $order->id) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No orders found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-6">
        <nav class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
                <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">{{ isset($ordersCount) ? $ordersCount : '0' }}</span> orders
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Previous</span>
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <a href="#" aria-current="page" class="z-10 bg-blue-600 border-blue-600 text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium">1</a>
                        <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">2</a>
                        <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">3</a>
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>
                        <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">8</a>
                        <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">9</a>
                        <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">10</a>
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Next</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </nav>
                </div>
            </div>
        </nav>
    </div>
</div>
@endsection 