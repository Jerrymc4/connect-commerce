@extends('layouts.storefront')

@section('title', 'My Wishlist')

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
                    <a href="{{ route('customer.profile') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-user mr-2"></i> Account Details
                    </a>
                    <a href="{{ route('customer.wishlist') }}" class="block py-2 px-3 bg-blue-50 text-blue-700 rounded-md font-medium">
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
                    <h1 class="text-2xl font-bold">My Wishlist</h1>
                    <span class="text-gray-500">0 items</span>
                </div>
                
                <!-- Empty wishlist state -->
                <div class="bg-gray-50 rounded-lg p-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-heart text-5xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">Your wishlist is empty</h2>
                    <p class="text-gray-600 mb-6">Add items you love to your wishlist. Review them anytime and easily move them to the cart.</p>
                    <a href="{{ route('storefront.products') }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                        Browse Products
                    </a>
                </div>
                
                <!-- Wishlist items grid would go here -->
                <div class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                    <!-- Wishlist item cards would be dynamically added here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 