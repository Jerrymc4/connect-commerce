@extends('layouts.storefront')

@section('title', 'My Account - Payment Methods')

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
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Payment Methods</h1>
                    <a href="{{ route('customer.payment-methods.create') }}" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add New Card
                    </a>
                </div>
                
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                <div class="space-y-4">
                    @forelse($paymentMethods as $method)
                        <div class="border border-gray-200 rounded-lg p-4 flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex items-center mb-4 md:mb-0">
                                <div class="text-2xl mr-4">
                                    @if($method->card_type == 'Visa')
                                        <i class="fab fa-cc-visa text-blue-700"></i>
                                    @elseif($method->card_type == 'Mastercard')
                                        <i class="fab fa-cc-mastercard text-red-500"></i>
                                    @elseif($method->card_type == 'American Express')
                                        <i class="fab fa-cc-amex text-blue-500"></i>
                                    @elseif($method->card_type == 'Discover')
                                        <i class="fab fa-cc-discover text-orange-500"></i>
                                    @else
                                        <i class="fas fa-credit-card text-gray-500"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium">{{ $method->card_type }} ending in {{ $method->last_four }}</p>
                                    <p class="text-sm text-gray-600">Expires {{ $method->expiry_month }}/{{ $method->expiry_year }}</p>
                                    @if($method->is_default)
                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded mt-1">Default</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <form method="POST" action="{{ route('customer.payment-methods.destroy', $method->id) }}" onsubmit="return confirm('Are you sure you want to remove this payment method?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="bg-gray-50 rounded-lg p-8 text-center">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-credit-card text-5xl"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-700 mb-2">No payment methods</h2>
                            <p class="text-gray-600 mb-6">You haven't added any payment methods yet.</p>
                            <a href="{{ route('customer.payment-methods.create') }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                                Add Payment Method
                            </a>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-semibold mb-2">About Payment Methods</h2>
                    <p class="text-gray-600">
                        Your payment information is stored securely and will be used for future purchases and subscriptions. You can add multiple payment methods and set a default.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 