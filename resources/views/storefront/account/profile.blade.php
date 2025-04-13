@extends('layouts.storefront')

@section('title', 'My Account - Profile')

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
                    <a href="{{ route('customer.payment-methods') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-credit-card mr-2"></i> Payment Methods
                    </a>
                    <a href="{{ route('customer.profile') }}" class="block py-2 px-3 bg-blue-50 text-blue-700 rounded-md font-medium">
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
                <h1 class="text-2xl font-bold mb-6">Account Details</h1>
                
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('customer.profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                            @error('name')
                                <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                            @error('email')
                                <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="phone" class="block text-gray-700 text-sm font-medium mb-2">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                        @error('phone')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Save Changes
                        </button>
                        
                        <a href="{{ route('customer.account') }}" class="ml-4 text-gray-600 hover:underline">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 