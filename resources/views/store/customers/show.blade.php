@extends('layouts.store')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Customer Profile</h1>
        <div class="flex space-x-3">
            <a href="{{ route('store.customers.edit', $customer->id, false) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('store.customers', [], false) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Customer Information Card -->
        <div class="md:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="flex flex-col items-center text-center p-6 bg-gradient-to-b from-blue-50 to-white dark:from-blue-900/50 dark:to-gray-800">
                    <div class="w-24 h-24 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center text-blue-500 dark:text-blue-300 text-4xl mb-4">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $customer->first_name }} {{ $customer->last_name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Customer since {{ $customer->created_at->format('M d, Y') }}</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Information</h3>
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-blue-500 w-5"></i>
                                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $customer->email }}</span>
                                </div>
                                @if($customer->phone)
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-green-500 w-5"></i>
                                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $customer->phone }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        @if($customer->defaultAddress())
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</h3>
                            <div class="mt-2 text-gray-900 dark:text-gray-100">
                                @if($customer->defaultAddress()->address_line_1)
                                <p>{{ $customer->defaultAddress()->address_line_1 }}</p>
                                @endif
                                @if($customer->defaultAddress()->address_line_2)
                                <p>{{ $customer->defaultAddress()->address_line_2 }}</p>
                                @endif
                                <p>
                                    @if($customer->defaultAddress()->city){{ $customer->defaultAddress()->city }}@endif
                                    @if($customer->defaultAddress()->city && $customer->defaultAddress()->state), @endif
                                    @if($customer->defaultAddress()->state){{ $customer->defaultAddress()->state }}@endif
                                    @if(($customer->defaultAddress()->city || $customer->defaultAddress()->state) && $customer->defaultAddress()->zipcode) @endif
                                    @if($customer->defaultAddress()->zipcode){{ $customer->defaultAddress()->zipcode }}@endif
                                </p>
                                @if($customer->defaultAddress()->country)
                                <p>{{ $customer->defaultAddress()->country }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Info</h3>
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Customer ID</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $customer->id }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Active
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="space-y-3">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Actions</h3>
                        <div class="space-y-2">
                            <form action="{{ route('store.customers.destroy', $customer->id, false) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.');" 
                                  class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex justify-center items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/40 rounded-md transition-colors">
                                    <i class="fas fa-trash-alt mr-2"></i> Delete Customer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order History -->
        <div class="md:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        <i class="fas fa-shopping-cart mr-2 text-blue-500"></i>
                        Order History
                    </h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ count($orders) }} Orders
                    </span>
                </div>
                <div class="overflow-x-auto">
                    @if(count($orders) > 0)
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order #</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium text-gray-900 dark:text-gray-100">#{{ $order->order_number }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($order->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        Pending
                                    </span>
                                    @elseif($order->status == 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        Paid
                                    </span>
                                    @elseif($order->status == 'shipped')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                        Shipped
                                    </span>
                                    @elseif($order->status == 'delivered')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Delivered
                                    </span>
                                    @elseif($order->status == 'cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Cancelled
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                        {{ $order->status_text }}
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                    ${{ number_format($order->total, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('store.orders.show', $order->id, false) }}" 
                                       class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200 p-1 rounded hover:bg-blue-100 dark:hover:bg-blue-900/20"
                                       title="View Order Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="text-center py-12">
                        <div class="text-gray-500 dark:text-gray-400">
                            <i class="fas fa-shopping-cart text-4xl mb-3 opacity-50"></i>
                            <p class="text-lg">No orders yet</p>
                            <p class="text-sm mt-2">This customer hasn't placed any orders.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Activity -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        <i class="fas fa-chart-line mr-2 text-blue-500"></i>
                        Customer Activity
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-br from-blue-50 to-white dark:from-blue-900/50 dark:to-gray-800 rounded-lg p-4 shadow-sm">
                            <div class="text-blue-500 text-xl mb-2">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Orders</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($orders) }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-50 to-white dark:from-green-900/50 dark:to-gray-800 rounded-lg p-4 shadow-sm">
                            <div class="text-green-500 text-xl mb-2">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Spent</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($orders->sum('total'), 2) }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-purple-50 to-white dark:from-purple-900/50 dark:to-gray-800 rounded-lg p-4 shadow-sm">
                            <div class="text-purple-500 text-xl mb-2">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Last Purchase</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                @if($orders->count() > 0)
                                {{ $orders->sortByDesc('created_at')->first()->created_at->format('M d, Y') }}
                                @else
                                Never
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 