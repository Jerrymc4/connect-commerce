@extends('layouts.storefront')

@section('title', 'My Account')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Account sidebar -->
        <div class="w-full md:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">My Account</h2>
                <nav class="space-y-1">
                    <a href="{{ route('customer.account') }}" class="block py-2 px-3 bg-blue-50 text-blue-700 rounded-md font-medium">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('customer.orders') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-shopping-bag mr-2"></i> Orders
                    </a>
                    <a href="{{ route('customer.addresses') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-map-marker-alt mr-2"></i> Addresses
                    </a>
                    <a href="{{ route('customer.profile') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-user mr-2"></i> Account Details
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
                <h1 class="text-2xl font-bold mb-6">Account Dashboard</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <h2 class="text-lg font-semibold mb-3">Account Information</h2>
                        <p class="text-gray-600 mb-1">{{ Auth::guard('customer')->user()->name }}</p>
                        <p class="text-gray-600 mb-4">{{ Auth::guard('customer')->user()->email }}</p>
                        <a href="{{ route('customer.profile') }}" class="text-blue-600 hover:underline">Edit</a>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <h2 class="text-lg font-semibold mb-3">Recent Orders</h2>
                        <p class="text-gray-600 mb-4">You have no recent orders.</p>
                        <a href="{{ route('customer.orders') }}" class="text-blue-600 hover:underline">View All Orders</a>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-semibold mb-3">Wishlist</h2>
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <p class="text-gray-600 mb-2">You don't have any saved items in your wishlist yet.</p>
                        <a href="{{ route('storefront.products.index') }}" class="text-blue-600 hover:underline">Browse products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 