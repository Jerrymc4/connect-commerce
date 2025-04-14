@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $store->name ?? 'Store Details' }}</h1>
            <p class="text-gray-600 mt-1">Store ID: {{ $store->id ?? 'N/A' }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.stores.edit', $store->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-edit mr-2"></i> Edit Store
            </a>
            <a href="https://{{ $store->domain }}" target="_blank" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition flex items-center">
                <i class="fas fa-external-link-alt mr-2"></i> Visit Store
            </a>
        </div>
    </div>
    
    <!-- Store Performance -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-100 rounded-lg p-4 border border-blue-200 shadow-md">
            <div class="flex items-center">
                <div class="bg-blue-600 rounded-full p-3 mr-4">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($store->total_revenue ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-100 rounded-lg p-4 border border-gray-200 shadow-md">
            <div class="flex items-center">
                <div class="bg-black rounded-full p-3 mr-4">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $store->orders_count ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-100 rounded-lg p-4 border border-gray-200 shadow-md">
            <div class="flex items-center">
                <div class="bg-gray-700 rounded-full p-3 mr-4">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium">Products</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $store->products_count ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-blue-100 rounded-lg p-4 border border-blue-200 shadow-md">
            <div class="flex items-center">
                <div class="bg-blue-600 rounded-full p-3 mr-4">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium">Customers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $store->customers_count ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Store Information -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Basic Information -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
            <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="fas fa-store text-blue-600 mr-2"></i> Store Information
            </h2>
            
            <div class="mb-6 flex items-center">
                <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 bg-blue-100 flex items-center justify-center">
                    @if($store->logo ?? null)
                        <img src="{{ $store->logo }}" alt="{{ $store->name }}" class="h-full w-full object-contain">
                    @else
                        <i class="fas fa-store text-blue-600 text-3xl"></i>
                    @endif
                </div>
                <div class="ml-4">
                    <div class="text-lg font-medium text-gray-900">{{ $store->name ?? 'N/A' }}</div>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                        isset($store) && $store->status == 'active' ? 'bg-blue-100 text-blue-800' : 
                        (isset($store) && $store->status == 'inactive' ? 'bg-gray-100 text-gray-800' : 
                        'bg-gray-100 text-gray-800') 
                    }}">
                        {{ isset($store) ? ucfirst($store->status) : 'Unknown' }}
                    </span>
                </div>
            </div>
            
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-500">Created:</span>
                    <span class="ml-2 text-sm text-gray-900">{{ isset($store) && $store->created_at ? $store->created_at->format('M d, Y') : 'N/A' }}</span>
                </div>
                
                <div>
                    <span class="text-sm font-medium text-gray-500">Description:</span>
                    <p class="mt-1 text-sm text-gray-900">{{ $store->description ?? 'No description available.' }}</p>
                </div>
                
                <div>
                    <span class="text-sm font-medium text-gray-500">Subscription Plan:</span>
                    <span class="ml-2 text-sm text-gray-900">{{ $store->plan_name ?? 'N/A' }}</span>
                </div>
                
                <div>
                    <span class="text-sm font-medium text-gray-500">Trial Ends:</span>
                    <span class="ml-2 text-sm text-gray-900">{{ isset($store) && $store->trial_ends_at ? $store->trial_ends_at->format('M d, Y') : 'No trial' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Domain Information -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
            <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="fas fa-globe text-blue-600 mr-2"></i> Domain Information
            </h2>
            
            <div class="space-y-4">
                <div>
                    <span class="text-sm font-medium text-gray-500">Store Domain:</span>
                    <div class="mt-1 flex items-center">
                        <span class="text-sm text-gray-900">{{ $store->domain ?? 'N/A' }}</span>
                        <a href="https://{{ $store->domain ?? '' }}" target="_blank" class="ml-2 text-xs text-blue-600 hover:text-blue-800">
                            <i class="fas fa-external-link-alt"></i> Visit
                        </a>
                    </div>
                </div>
                
                <div>
                    <span class="text-sm font-medium text-gray-500">Custom Domain:</span>
                    <div class="mt-1 flex items-center">
                        <span class="text-sm text-gray-900">{{ $store->custom_domain ?? 'None' }}</span>
                        @if($store->custom_domain ?? null)
                            <a href="https://{{ $store->custom_domain }}" target="_blank" class="ml-2 text-xs text-blue-600 hover:text-blue-800">
                                <i class="fas fa-external-link-alt"></i> Visit
                            </a>
                        @endif
                    </div>
                </div>
                
                <div>
                    <span class="text-sm font-medium text-gray-500">DNS Status:</span>
                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                        isset($store) && ($store->dns_status ?? '') == 'verified' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' 
                    }}">
                        {{ isset($store) && isset($store->dns_status) ? ucfirst($store->dns_status) : 'Pending' }}
                    </span>
                </div>
                
                <div>
                    <span class="text-sm font-medium text-gray-500">SSL Certificate:</span>
                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                        isset($store) && ($store->ssl_status ?? '') == 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' 
                    }}">
                        {{ isset($store) && isset($store->ssl_status) ? ucfirst($store->ssl_status) : 'Pending' }}
                    </span>
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200">
                <h3 class="text-sm font-medium text-gray-900 mb-2">Features</h3>
                <div class="space-y-2">
                    @if(isset($store) && isset($store->features) && is_array($store->features))
                        @foreach($store->features as $feature)
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                                <span class="text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $feature)) }}</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-500">No special features enabled</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Owner Information -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-md">
            <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="fas fa-user text-blue-600 mr-2"></i> Owner Information
            </h2>
            
            <div class="mb-4 flex items-center">
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-user text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900">{{ $store->owner_name ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-500">Store Owner</div>
                </div>
            </div>
            
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-500">Email:</span>
                    <span class="ml-2 text-sm text-gray-900">{{ $store->owner_email ?? 'N/A' }}</span>
                </div>
                
                <div>
                    <span class="text-sm font-medium text-gray-500">Phone:</span>
                    <span class="ml-2 text-sm text-gray-900">{{ $store->owner_phone ?? 'N/A' }}</span>
                </div>
                
                <div>
                    <span class="text-sm font-medium text-gray-500">User Account:</span>
                    <div class="mt-1">
                        @if(isset($store) && isset($store->user))
                            <a href="{{ route('admin.users.view', $store->user->id) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                {{ $store->user->name }} ({{ $store->user->email }})
                            </a>
                        @else
                            <span class="text-sm text-gray-500">No user account linked</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Orders</h2>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Order ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total</th>
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
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                                    $order->status == 'completed' ? 'bg-blue-100 text-blue-800' : 
                                    ($order->status == 'processing' ? 'bg-gray-100 text-gray-800' : 
                                    'bg-gray-100 text-gray-800') 
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($order->total, 2) }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No recent orders</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Recent Products -->
    <div>
        <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Products</h2>
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Product</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">SKU</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Stock</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($recentProducts) && count($recentProducts) > 0)
                        @foreach($recentProducts as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-md flex items-center justify-center">
                                        @if($product->image)
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-md object-cover">
                                        @else
                                            <i class="fas fa-box text-blue-600"></i>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $product->category }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->sku }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                                    $product->status == 'active' ? 'bg-blue-100 text-blue-800' : 
                                    ($product->status == 'draft' ? 'bg-gray-100 text-gray-800' : 
                                    'bg-gray-100 text-gray-800') 
                                }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No products found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex flex-col md:flex-row md:justify-between space-y-3 md:space-y-0">
        <div class="flex space-x-3">
            <button type="button" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition flex items-center">
                <i class="fas fa-sync-alt mr-2"></i> Refresh Store Cache
            </button>
            <button type="button" class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-yellow-600 transition flex items-center">
                <i class="fas fa-pause-circle mr-2"></i> Suspend Store
            </button>
        </div>
        
        <form method="POST" action="{{ route('admin.stores.delete', $store->id ?? 0) }}" onsubmit="return confirm('Are you sure you want to delete this store? This action cannot be undone and will delete all associated data.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition flex items-center">
                <i class="fas fa-trash mr-2"></i> Delete Store
            </button>
        </form>
    </div>
</div>
@endsection 