@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Store Dashboard</h1>
    
    <!-- Dashboard Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-blue-50 rounded-lg p-6 border border-blue-100">
            <div class="flex items-center">
                <div class="bg-blue-500 rounded-full p-3 mr-4">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($totalRevenue ?? 0, 2) }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i> 12% from last month
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-green-50 rounded-lg p-6 border border-green-100">
            <div class="flex items-center">
                <div class="bg-green-500 rounded-full p-3 mr-4">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalOrders ?? 0 }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i> 8% from last month
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-indigo-50 rounded-lg p-6 border border-indigo-100">
            <div class="flex items-center">
                <div class="bg-indigo-500 rounded-full p-3 mr-4">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Products</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalProducts ?? 0 }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i> 5% from last month
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-purple-50 rounded-lg p-6 border border-purple-100">
            <div class="flex items-center">
                <div class="bg-purple-500 rounded-full p-3 mr-4">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Customers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalCustomers ?? 0 }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i> 15% from last month
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Orders</h2>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($recentOrders) && count($recentOrders) > 0)
                        @foreach($recentOrders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : ($order->status == 'processing' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.orders.view', $order->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No recent orders</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Store Performance -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Top Products</h2>
            @if(isset($topProducts) && count($topProducts) > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($topProducts as $product)
                    <li class="py-3 flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="bg-gray-200 rounded-md w-10 h-10 flex items-center justify-center mr-3">
                                <i class="fas fa-box text-gray-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-sm text-gray-500">{{ $product->sold_count }} sold</p>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">${{ number_format($product->price, 2) }}</span>
                    </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 text-sm">No product data available</p>
            @endif
        </div>
        
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Monthly Revenue</h2>
            <div class="h-80 bg-gray-50 rounded-lg p-4 flex items-end justify-between space-x-2">
                <!-- Placeholder for actual chart -->
                <div class="bg-blue-500 w-1/12 rounded-t-sm h-1/5"></div>
                <div class="bg-blue-500 w-1/12 rounded-t-sm h-2/6"></div>
                <div class="bg-blue-500 w-1/12 rounded-t-sm h-1/4"></div>
                <div class="bg-blue-500 w-1/12 rounded-t-sm h-3/6"></div>
                <div class="bg-blue-500 w-1/12 rounded-t-sm h-1/2"></div>
                <div class="bg-blue-500 w-1/12 rounded-t-sm h-4/6"></div>
                <div class="bg-blue-500 w-1/12 rounded-t-sm h-3/4"></div>
                <div class="bg-blue-500 w-1/12 rounded-t-sm h-5/6"></div>
                <div class="bg-blue-500 w-1/12 rounded-t-sm h-2/3"></div>
                <div class="bg-blue-500 w-1/12 rounded-t-sm h-4/5"></div>
                <div class="bg-blue-500 w-1/12 rounded-t-sm h-5/6"></div>
                <div class="bg-blue-500 w-1/12 rounded-t-sm h-full"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2">
                <span>Jan</span>
                <span>Feb</span>
                <span>Mar</span>
                <span>Apr</span>
                <span>May</span>
                <span>Jun</span>
                <span>Jul</span>
                <span>Aug</span>
                <span>Sep</span>
                <span>Oct</span>
                <span>Nov</span>
                <span>Dec</span>
            </div>
        </div>
    </div>
</div>
@endsection 