@extends('layouts.storefront')

@section('title', 'Add Payment Method')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Account sidebar -->
        <div class="w-full md:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">My Account</h2>
                <nav class="space-y-1">
                    <a href="{{ route('customer.account') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('customer.orders') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-shopping-bag mr-2"></i> Orders
                    </a>
                    <a href="{{ route('customer.addresses') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-map-marker-alt mr-2"></i> Addresses
                    </a>
                    <a href="{{ route('customer.payment-methods') }}" class="block py-2 px-3 bg-blue-50 text-blue-700 rounded-md font-medium">
                        <i class="fas fa-credit-card mr-2"></i> Payment Methods
                    </a>
                    <a href="{{ route('customer.profile') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-user mr-2"></i> Account Details
                    </a>
                    <a href="{{ route('customer.password') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-lock mr-2"></i> Change Password
                    </a>
                    <a href="{{ route('customer.wishlist') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-heart mr-2"></i> Wishlist
                    </a>
                    <form method="POST" action="{{ route('customer.logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </nav>
            </div>
        </div>
        
        <!-- Account content -->
        <div class="w-full md:w-3/4">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-6">
                    <a href="{{ route('customer.payment-methods') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-2xl font-bold">Add Payment Method</h1>
                </div>
                
                <form method="POST" action="{{ route('customer.payment-methods.store') }}">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="card_number" class="block text-gray-700 text-sm font-medium mb-2">Card Number</label>
                        <input type="text" name="card_number" id="card_number" placeholder="1234 5678 9012 3456" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                        @error('card_number')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="card_holder" class="block text-gray-700 text-sm font-medium mb-2">Cardholder Name</label>
                        <input type="text" name="card_holder" id="card_holder" placeholder="John Doe" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                        @error('card_holder')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div>
                            <label for="expiry_month" class="block text-gray-700 text-sm font-medium mb-2">Expiry Month</label>
                            <select name="expiry_month" id="expiry_month" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ sprintf('%02d', $i) }}</option>
                                @endfor
                            </select>
                            @error('expiry_month')
                                <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="expiry_year" class="block text-gray-700 text-sm font-medium mb-2">Expiry Year</label>
                            <select name="expiry_year" id="expiry_year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                @for ($i = date('Y'); $i <= date('Y') + 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            @error('expiry_year')
                                <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="cvv" class="block text-gray-700 text-sm font-medium mb-2">CVV</label>
                            <input type="text" name="cvv" id="cvv" placeholder="123" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                            @error('cvv')
                                <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_default" id="is_default" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="is_default" class="ml-2 block text-sm text-gray-700">
                                Set as default payment method
                            </label>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-md mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-0.5">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-700">Secure Payment Processing</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Your card information is encrypted and securely processed. We do not store your full card details on our servers.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-6 flex justify-between">
                        <a href="{{ route('customer.payment-methods') }}" class="text-gray-600 hover:underline">
                            Cancel
                        </a>
                        <button type="submit" class="py-2 px-4 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors" style="background-color: var(--primary-color)">
                            Add Payment Method
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Simple card number formatting
    document.getElementById('card_number').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        let formattedValue = '';
        
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        
        e.target.value = formattedValue;
    });
    
    // Simple CVV validation
    document.getElementById('cvv').addEventListener('input', function (e) {
        e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
    });
</script>
@endpush 