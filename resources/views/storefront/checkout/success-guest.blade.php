@extends('layouts.storefront')

@section('title', 'Order Confirmation')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-green-100 text-green-600 mb-4">
                <i class="fas fa-check-circle text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Thank You for Your Order!</h1>
            <p class="text-gray-600 mt-2">Order #{{ str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT) }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-2">Order Confirmation</h2>
                <p class="text-gray-600">
                    We've sent a confirmation email to <strong>{{ Auth::guard('customer')->user()->email }}</strong> with your order details.
                </p>
            </div>
            
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="text-lg font-medium mb-4">Would you like to create an account?</h3>
                <p class="text-gray-600 mb-4">
                    Creating an account makes it easier to track your orders, manage your shipping addresses, and checkout faster next time.
                </p>
                
                <form method="POST" action="{{ route('storefront.checkout.create-account') }}" class="mb-6">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-medium mb-2">
                            Create Password <span class="text-red-600">*</span>
                        </label>
                        <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                        @error('password')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">
                            Confirm Password <span class="text-red-600">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Create Account
                        </button>
                        <a href="{{ route('storefront.home') }}" class="text-gray-600 hover:underline">
                            No, thanks
                        </a>
                    </div>
                </form>
                
                <div class="text-sm text-gray-500">
                    <p><i class="fas fa-info-circle mr-1"></i> Your email <strong>{{ Auth::guard('customer')->user()->email }}</strong> will be used as your username.</p>
                </div>
            </div>
        </div>
        
        <div class="flex justify-center">
            <a href="{{ route('storefront.home') }}" class="text-blue-600 hover:underline flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Return to Store
            </a>
        </div>
    </div>
</div>
@endsection 