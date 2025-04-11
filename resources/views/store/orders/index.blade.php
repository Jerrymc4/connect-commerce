@extends('layouts.store')

@section('content')
<div class="container px-3 mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">Orders</h1>
            <p class="text-secondary mt-1">Manage and process customer orders</p>
        </div>
    </div>
    
    <!-- Flash Messages -->
    @if(session('success'))
    <div id="success-alert" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <div class="flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-4 text-green-700 hover:text-green-900 focus:outline-none" onclick="closeAlert('success-alert')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    @if(session('error'))
    <div id="error-alert" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <div class="flex justify-between items-center">
            <span>{{ session('error') }}</span>
            <button type="button" class="ml-4 text-red-700 hover:text-red-900 focus:outline-none" onclick="closeAlert('error-alert')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    <!-- Order Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-card rounded-lg border border-border-color p-4 text-center">
            <p class="text-sm font-medium text-secondary">All Orders</p>
            <p class="text-xl font-bold text-primary mt-1">{{ $orders->total() }}</p>
        </div>
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800 p-4 text-center">
            <p class="text-sm font-medium text-yellow-700 dark:text-yellow-400">Pending</p>
            <p class="text-xl font-bold text-yellow-800 dark:text-yellow-300 mt-1">
                {{ $orders->where('status', 'pending')->count() }}
            </p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-4 text-center">
            <p class="text-sm font-medium text-blue-700 dark:text-blue-400">Processing</p>
            <p class="text-xl font-bold text-blue-800 dark:text-blue-300 mt-1">
                {{ $orders->where('status', 'processing')->count() }}
            </p>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800 p-4 text-center">
            <p class="text-sm font-medium text-green-700 dark:text-green-400">Completed</p>
            <p class="text-xl font-bold text-green-800 dark:text-green-300 mt-1">
                {{ $orders->where('status', 'completed')->count() }}
            </p>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800 p-4 text-center">
            <p class="text-sm font-medium text-red-700 dark:text-red-400">Cancelled</p>
            <p class="text-xl font-bold text-red-800 dark:text-red-300 mt-1">
                {{ $orders->where('status', 'cancelled')->count() }}
            </p>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="card-enhanced">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-primary flex items-center">
                <i class="fas fa-shopping-cart text-blue-500 mr-2"></i>
                Orders
            </h3>
            <div class="flex items-center space-x-2">
                <form action="{{ route('store.orders', [], false) }}" method="get" class="flex items-center space-x-2">
                    <!-- Status Filter -->
                    <select name="status" class="form-control text-sm py-1" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                    
                    <!-- Search -->
                    <div class="flex">
                        <input type="text" name="search" placeholder="Search orders..." value="{{ request('search') }}" class="form-control rounded-r-none border-r-0 text-sm py-1">
                        <button type="submit" class="btn-primary rounded-l-none py-1 px-3">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table-enhanced">
                <thead>
                    <tr>
                        <th class="text-center">Order #</th>
                        <th class="text-center">Customer</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Payment</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="text-center font-medium text-primary">
                            #{{ $order->order_number }}
                        </td>
                        <td class="text-center">
                            <div class="font-medium text-primary">{{ $order->customer_name }}</div>
                            <div class="text-secondary text-sm">{{ $order->customer_email }}</div>
                        </td>
                        <td class="text-center text-secondary">
                            {{ $order->created_at->format('M d, Y') }}<br>
                            <span class="text-xs">{{ $order->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="text-center">
                            @if($order->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($order->status === 'processing')
                                <span class="badge badge-primary">Processing</span>
                            @elseif($order->status === 'completed')
                                <span class="badge badge-success">Completed</span>
                            @elseif($order->status === 'cancelled')
                                <span class="badge badge-danger">Cancelled</span>
                            @elseif($order->status === 'refunded')
                                <span class="badge badge-info">Refunded</span>
                            @else
                                <span class="badge badge-secondary">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="text-secondary">
                                @if($order->payment_status === 'paid')
                                    <span class="badge badge-success">Paid</span>
                                @elseif($order->payment_status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($order->payment_status === 'failed')
                                    <span class="badge badge-danger">Failed</span>
                                @elseif($order->payment_status === 'refunded')
                                    <span class="badge badge-info">Refunded</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($order->payment_status) }}</span>
                                @endif
                            </div>
                            <div class="text-secondary text-xs mt-1">
                                {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}
                            </div>
                        </td>
                        <td class="text-center font-medium text-primary">${{ number_format($order->total, 2) }}</td>
                        <td class="text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('store.orders.show', $order->id, false) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('store.orders.edit', $order->id, false) }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('store.orders.invoice', $order->id, false) }}" class="text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300" title="Invoice">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-secondary">No orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body border-t border-border-color">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Close alert function
    function closeAlert(id) {
        document.getElementById(id).style.display = 'none';
    }
    
    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        // Success alerts
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(function() {
                successAlert.style.opacity = '0';
                successAlert.style.transition = 'opacity 1s';
                setTimeout(function() {
                    successAlert.style.display = 'none';
                }, 1000);
            }, 5000);
        }
        
        // Error alerts
        const errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            setTimeout(function() {
                errorAlert.style.opacity = '0';
                errorAlert.style.transition = 'opacity 1s';
                setTimeout(function() {
                    errorAlert.style.display = 'none';
                }, 1000);
            }, 5000);
        }
    });
</script>
@endpush 