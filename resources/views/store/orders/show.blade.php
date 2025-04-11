@extends('layouts.store')

@section('content')
<div class="container px-3 mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">Order #{{ $order->order_number }}</h1>
            <p class="text-secondary mt-1">{{ $order->created_at->format('F j, Y \a\t h:i A') }}</p>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('store.orders', [], false) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Orders
            </a>
            <a href="{{ route('store.orders.edit', $order->id, false) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Edit Order
            </a>
            <a href="{{ route('store.orders.invoice', $order->id, false) }}" class="btn-info">
                <i class="fas fa-file-invoice mr-2"></i>
                Invoice
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
    
    <!-- Order Status & Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="card-enhanced">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-primary flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Order Status
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <p class="text-sm text-secondary mb-1">Order Status</p>
                    <div>
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
                    </div>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-secondary mb-1">Payment Status</p>
                    <div>
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
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-secondary mb-1">Payment Method</p>
                    <p class="font-medium text-primary">
                        {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}
                    </p>
                </div>
                
                @if($order->tracking_number)
                <div class="mb-4">
                    <p class="text-sm text-secondary mb-1">Tracking Number</p>
                    <p class="font-medium text-primary">{{ $order->tracking_number }}</p>
                </div>
                @endif
                
                @if($order->shipping_provider)
                <div class="mb-4">
                    <p class="text-sm text-secondary mb-1">Shipping Provider</p>
                    <p class="font-medium text-primary">{{ $order->shipping_provider }}</p>
                </div>
                @endif
                
                <div>
                    <p class="text-sm text-secondary mb-1">Date Placed</p>
                    <p class="font-medium text-primary">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
        
        <div class="card-enhanced">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-primary flex items-center">
                    <i class="fas fa-user text-blue-500 mr-2"></i>
                    Customer Information
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <p class="text-sm text-secondary mb-1">Customer</p>
                    <p class="font-medium text-primary">{{ $order->customer_name }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-secondary mb-1">Email</p>
                    <p class="font-medium text-primary">{{ $order->customer_email }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-secondary mb-1">Phone</p>
                    <p class="font-medium text-primary">{{ $order->customer_phone ?? 'N/A' }}</p>
                </div>
                
                @if($order->notes)
                <div>
                    <p class="text-sm text-secondary mb-1">Order Notes</p>
                    <p class="text-primary">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <div class="card-enhanced">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-primary flex items-center">
                    <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                    Shipping Address
                </h3>
            </div>
            <div class="card-body">
                <address class="not-italic">
                    <p class="font-medium text-primary">{{ $order->shipping_name }}</p>
                    <p class="text-secondary">{{ $order->shipping_address_line1 }}</p>
                    @if($order->shipping_address_line2)
                        <p class="text-secondary">{{ $order->shipping_address_line2 }}</p>
                    @endif
                    <p class="text-secondary">
                        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}
                    </p>
                    <p class="text-secondary">{{ $order->shipping_country }}</p>
                </address>
            </div>
        </div>
    </div>
    
    <!-- Order Items -->
    <div class="card-enhanced mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-primary flex items-center">
                <i class="fas fa-shopping-basket text-blue-500 mr-2"></i>
                Order Items
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
                                <div class="h-12 w-12 flex-shrink-0 rounded bg-gray-100 dark:bg-gray-700 mr-3 flex items-center justify-center overflow-hidden">
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
                        <td colspan="4" class="text-right font-medium">Subtotal:</td>
                        <td class="text-center font-medium">${{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr>
                        <td colspan="4" class="text-right font-medium">Discount:</td>
                        <td class="text-center text-green-600 dark:text-green-400 font-medium">-${{ number_format($order->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="4" class="text-right font-medium">Shipping:</td>
                        <td class="text-center font-medium">${{ number_format($order->shipping_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right font-medium">Tax:</td>
                        <td class="text-center font-medium">${{ number_format($order->tax_amount, 2) }}</td>
                    </tr>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <td colspan="4" class="text-right font-bold">Total:</td>
                        <td class="text-center font-bold text-lg">${{ number_format($order->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-between gap-4 mt-8">
        <div>
            <a href="{{ route('store.orders', [], false) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Orders
            </a>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('store.orders.edit', $order->id, false) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Edit Order
            </a>
            <a href="{{ route('store.orders.invoice', $order->id, false) }}" class="btn-info">
                <i class="fas fa-file-invoice mr-2"></i>
                Invoice
            </a>
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
    });
</script>
@endpush 