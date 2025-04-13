@extends('layouts.admin.dashboard')

@section('content')
<div class="container px-3 mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">Invoice #{{ $order->order_number }}</h1>
            <p class="text-secondary mt-1">{{ $order->created_at->format('F j, Y') }}</p>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('admin.orders.show', $order->id, false) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Order
            </a>
            <button onclick="window.print()" class="btn-primary print:hidden">
                <i class="fas fa-print mr-2"></i>
                Print Invoice
            </button>
        </div>
    </div>
    
    <!-- Invoice Body -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-8 shadow-sm print:shadow-none">
        <!-- Invoice Header -->
        <div class="flex flex-col md:flex-row justify-between items-start mb-8 print:mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">INVOICE</h2>
                <p class="text-gray-600 dark:text-gray-400">Invoice #{{ $order->order_number }}</p>
                <p class="text-gray-600 dark:text-gray-400">Order Date: {{ $order->created_at->format('M d, Y') }}</p>
            </div>
            
            <div class="mt-4 md:mt-0 text-right">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</h3>
                <p class="text-gray-600 dark:text-gray-400">{{ tenant('name') }}</p>
                <p class="text-gray-600 dark:text-gray-400">{{ tenant('domain') }}</p>
            </div>
        </div>
        
        <hr class="my-6 border-gray-200 dark:border-gray-700 print:my-4">
        
        <!-- Customer & Shipping Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 print:mb-6">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-2">Bill To:</h3>
                <p class="font-medium text-gray-900 dark:text-white">{{ $order->customer_name }}</p>
                <p class="text-gray-600 dark:text-gray-400">{{ $order->customer_email }}</p>
                @if($order->customer_phone)
                    <p class="text-gray-600 dark:text-gray-400">{{ $order->customer_phone }}</p>
                @endif
            </div>
            
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-2">Ship To:</h3>
                <p class="font-medium text-gray-900 dark:text-white">{{ $order->shipping_name }}</p>
                <p class="text-gray-600 dark:text-gray-400">{{ $order->shipping_address_line1 }}</p>
                @if($order->shipping_address_line2)
                    <p class="text-gray-600 dark:text-gray-400">{{ $order->shipping_address_line2 }}</p>
                @endif
                <p class="text-gray-600 dark:text-gray-400">
                    {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}
                </p>
                <p class="text-gray-600 dark:text-gray-400">{{ $order->shipping_country }}</p>
            </div>
        </div>
        
        <!-- Order Details -->
        <table class="w-full text-left border-collapse mb-8 print:mb-6">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700">
                    <th class="py-3 px-4 font-semibold text-gray-900 dark:text-white">Item</th>
                    <th class="py-3 px-4 font-semibold text-gray-900 dark:text-white text-center">Price</th>
                    <th class="py-3 px-4 font-semibold text-gray-900 dark:text-white text-center">Quantity</th>
                    <th class="py-3 px-4 font-semibold text-gray-900 dark:text-white text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($order->orderItems as $item)
                <tr>
                    <td class="py-3 px-4 text-gray-900 dark:text-white">
                        <div class="font-medium">{{ $item->product_name }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">SKU: {{ $item->product_sku }}</div>
                    </td>
                    <td class="py-3 px-4 text-gray-900 dark:text-white text-center">${{ number_format($item->price, 2) }}</td>
                    <td class="py-3 px-4 text-gray-900 dark:text-white text-center">{{ $item->quantity }}</td>
                    <td class="py-3 px-4 text-gray-900 dark:text-white text-right font-medium">${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Order Summary -->
        <div class="flex justify-end mb-8 print:mb-6">
            <div class="w-full max-w-xs">
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex justify-between py-1">
                        <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                        <span class="font-medium text-gray-900 dark:text-white">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between py-1">
                        <span class="text-gray-600 dark:text-gray-400">Discount:</span>
                        <span class="font-medium text-green-600 dark:text-green-400">-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between py-1">
                        <span class="text-gray-600 dark:text-gray-400">Shipping:</span>
                        <span class="font-medium text-gray-900 dark:text-white">${{ number_format($order->shipping_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-gray-600 dark:text-gray-400">Tax:</span>
                        <span class="font-medium text-gray-900 dark:text-white">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-700 mt-2">
                        <span class="font-bold text-gray-900 dark:text-white">Total:</span>
                        <span class="font-bold text-gray-900 dark:text-white">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Thank You Note -->
        <div class="text-center mb-8 print:mb-6">
            <p class="text-gray-600 dark:text-gray-400">Thank you for your business!</p>
        </div>
        
        <!-- Notes & Payment Info -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($order->notes)
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Notes</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ $order->notes }}</p>
                </div>
                @endif
                
                <div class="{{ $order->notes ? '' : 'md:col-start-2' }}">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Payment Information</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        <span class="font-medium">Method:</span> {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-400">
                        <span class="font-medium">Status:</span> 
                        @if($order->payment_status === 'paid')
                            <span class="text-green-600 dark:text-green-400">Paid</span>
                        @elseif($order->payment_status === 'pending')
                            <span class="text-yellow-600 dark:text-yellow-400">Pending</span>
                        @elseif($order->payment_status === 'failed')
                            <span class="text-red-600 dark:text-red-400">Failed</span>
                        @elseif($order->payment_status === 'refunded')
                            <span class="text-blue-600 dark:text-blue-400">Refunded</span>
                        @else
                            {{ ucfirst($order->payment_status) }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Print Button (Bottom, non-printing) -->
    <div class="mt-8 text-center print:hidden">
        <button onclick="window.print()" class="btn-primary">
            <i class="fas fa-print mr-2"></i>
            Print Invoice
        </button>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        @page {
            margin: 0.5cm;
        }
        
        body {
            background-color: white;
            color: black;
        }
        
        a {
            color: black;
            text-decoration: none;
        }
        
        .print\:hidden {
            display: none !important;
        }
        
        .print\:mb-6 {
            margin-bottom: 1.5rem !important;
        }
        
        .print\:shadow-none {
            box-shadow: none !important;
        }
        
        .container {
            max-width: 100%;
            padding: 0;
        }
    }
</style>
@endpush 