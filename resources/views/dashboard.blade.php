@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                        @if(isset($storeName))
                            <p class="text-gray-600 mt-1">Store: {{ $storeName }}</p>
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        @if (session('status') === 'store-created')
                        <div class="bg-green-100 text-green-800 p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">
                                        Your store has been successfully created!
                                    </p>
                                    @if(session('store_url'))
                                    <p class="text-sm mt-1">
                                        Your store URL: <a href="https://{{ session('store_url') }}" class="font-medium underline" target="_blank">{{ session('store_url') }}</a>
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ request()->getSchemeAndHttpHost() }}">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Sales Card -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="bg-blue-500 rounded-full p-3 mr-4">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Total Sales</p>
                                <p class="text-2xl font-bold text-gray-900">$0.00</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Orders Card -->
                    <div class="bg-green-50 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="bg-green-500 rounded-full p-3 mr-4">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Orders</p>
                                <p class="text-2xl font-bold text-gray-900">0</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Products Card -->
                    <div class="bg-indigo-50 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="bg-indigo-500 rounded-full p-3 mr-4">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Products</p>
                                <p class="text-2xl font-bold text-gray-900">0</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customers Card -->
                    <div class="bg-purple-50 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="bg-purple-500 rounded-full p-3 mr-4">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Customers</p>
                                <p class="text-2xl font-bold text-gray-900">0</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-10">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Getting Started</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                                <span class="text-blue-600 font-bold text-lg">1</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Add Your First Product</h3>
                            <p class="text-gray-600">Start by adding products to your store's catalog.</p>
                            <a href="#" class="mt-4 inline-block text-blue-600 font-medium hover:text-blue-800">Add product →</a>
                        </div>
                        
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                                <span class="text-blue-600 font-bold text-lg">2</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Customize Your Store</h3>
                            <p class="text-gray-600">Personalize your store with your branding and theme.</p>
                            <a href="#" class="mt-4 inline-block text-blue-600 font-medium hover:text-blue-800">Theme settings →</a>
                        </div>
                        
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                                <span class="text-blue-600 font-bold text-lg">3</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Set Up Payments</h3>
                            <p class="text-gray-600">Connect your payment methods to start accepting orders.</p>
                            <a href="#" class="mt-4 inline-block text-blue-600 font-medium hover:text-blue-800">Payment settings →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 