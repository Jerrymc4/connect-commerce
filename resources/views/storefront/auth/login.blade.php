@extends('layouts.storefront')

@section('title', 'Customer Login')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="py-4 px-6 bg-gray-50 border-b">
            <h2 class="text-2xl font-bold text-gray-800">Customer Login</h2>
            <p class="text-sm text-gray-600 mt-1">Access your account to manage orders and preferences</p>
        </div>
        
        <div class="py-6 px-6">
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('customer.login.attempt') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                    <input type="email" name="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required autofocus>
                    @error('email')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                    <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    @error('password')
                        <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    
                    <a href="{{ route('customer.password.request') }}" class="text-sm text-blue-600 hover:underline">
                        Forgot your password?
                    </a>
                </div>
                
                <div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Sign In
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('customer.register') }}" class="text-blue-600 hover:underline">
                        Register now
                    </a>
                </p>
                
                <div class="mt-4">
                    <a href="{{ route('storefront.home') }}" class="text-sm text-gray-600 hover:underline">
                        <i class="fas fa-arrow-left mr-1"></i> Back to store
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 