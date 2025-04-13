@extends('layouts.admin.dashboard')

@section('content')
<div class="container px-3 mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">Edit Order #{{ $order->order_number }}</h1>
            <p class="text-secondary mt-1">Update order information and status</p>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.orders', [], false) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Orders
            </a>
            <a href="{{ route('admin.orders.show', $order->id, false) }}" class="btn-info">
                <i class="fas fa-eye mr-2"></i>
                View Order
            </a>
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
    
    <!-- Order Edit Form -->
    <div class="card-enhanced">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-primary flex items-center">
                <i class="fas fa-edit text-blue-500 mr-2"></i>
                Update Order Status
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.orders.update', $order->id, false) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Order Status -->
                    <div>
                        <label for="status" class="form-label">Order Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') border-red-500 @enderror" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Payment Status -->
                    <div>
                        <label for="payment_status" class="form-label">Payment Status <span class="text-red-500">*</span></label>
                        <select name="payment_status" id="payment_status" class="form-control @error('payment_status') border-red-500 @enderror" required>
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                        @error('payment_status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Tracking Number -->
                    <div>
                        <label for="tracking_number" class="form-label">Tracking Number</label>
                        <input type="text" name="tracking_number" id="tracking_number" value="{{ $order->tracking_number }}" class="form-control @error('tracking_number') border-red-500 @enderror">
                        @error('tracking_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Shipping Provider -->
                    <div>
                        <label for="shipping_provider" class="form-label">Shipping Provider</label>
                        <input type="text" name="shipping_provider" id="shipping_provider" value="{{ $order->shipping_provider }}" class="form-control @error('shipping_provider') border-red-500 @enderror">
                        @error('shipping_provider')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Order Notes -->
                <div class="mb-6">
                    <label for="notes" class="form-label">Order Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="form-control @error('notes') border-red-500 @enderror">{{ $order->notes }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.orders.show', $order->id, false) }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Update Order
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Order Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6 mb-6">
        <!-- Order Details -->
        <div class="card-enhanced">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-primary flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Order Details
                </h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-secondary mb-1">Order Number</p>
                        <p class="font-medium text-primary">#{{ $order->order_number }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-secondary mb-1">Date Placed</p>
                        <p class="font-medium text-primary">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-secondary mb-1">Payment Method</p>
                        <p class="font-medium text-primary">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-secondary mb-1">Total</p>
                        <p class="font-medium text-primary">${{ number_format($order->total, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Customer Information -->
        <div class="card-enhanced">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-primary flex items-center">
                    <i class="fas fa-user text-blue-500 mr-2"></i>
                    Customer Information
                </h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-secondary mb-1">Customer</p>
                        <p class="font-medium text-primary">{{ $order->customer_name }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-secondary mb-1">Email</p>
                        <p class="font-medium text-primary">{{ $order->customer_email }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-secondary mb-1">Phone</p>
                        <p class="font-medium text-primary">{{ $order->customer_phone ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-secondary mb-1">Address</p>
                        <p class="font-medium text-primary">{{ $order->shipping_city }}, {{ $order->shipping_state }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Items -->
    <div class="card-enhanced mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-primary flex items-center">
                <i class="fas fa-shopping-basket text-blue-500 mr-2"></i>
                Order Items (Read Only)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="table-enhanced">
                <thead>
                    <tr>
                        <th class="text-center">Product</th>
                        <th class="text-center">SKU</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td class="text-center">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 rounded bg-gray-100 dark:bg-gray-700 mr-3 flex items-center justify-center overflow-hidden">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                                    @else
                                        <i class="fas fa-box text-gray-400"></i>
                                    @endif
                                </div>
                                <div class="font-medium text-primary">
                                    {{ $item->product_name }}
                                </div>
                            </div>
                        </td>
                        <td class="text-center text-secondary">{{ $item->product_sku }}</td>
                        <td class="text-center">${{ number_format($item->price, 2) }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-center font-medium text-primary">${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t border-border-color">
                    <tr>
                        <td colspan="4" class="text-right font-medium">Total:</td>
                        <td class="text-center font-medium">${{ number_format($order->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex justify-end gap-3 mt-6">
        <a href="{{ route('admin.orders.show', $order->id, false) }}" class="btn-secondary">
            <i class="fas fa-times mr-2"></i>
            Cancel
        </a>
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