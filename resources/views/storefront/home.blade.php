@extends('layouts.storefront')

@section('title', $storeName)

@section('content')
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white">
        <div class="container mx-auto px-4 py-16 md:py-24">
            <div class="max-w-xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Welcome to {{ $storeName }}</h1>
                <p class="text-lg md:text-xl mb-8">Discover amazing products at great prices</p>
                <a href="{{ route('storefront.products.index') }}" class="bg-white text-purple-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors inline-block">
                    Shop Now
                </a>
            </div>
        </div>
    </div>
    
    <!-- Featured Categories -->
    <div class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-8">Shop by Category</h2>
            
            @if($featuredCategories->isEmpty())
                <div class="text-center p-8 bg-white rounded-lg shadow">
                    <p class="text-gray-500">No categories found.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($featuredCategories as $category)
                        <a href="{{ route('storefront.products.index', ['category' => $category->id]) }}" class="group">
                            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform transform hover:scale-105 h-full">
                                <div class="h-40 bg-gray-200 overflow-hidden">
                                    <img src="{{ $category->image ?? 'https://via.placeholder.com/300x150?text=' . urlencode($category->name) }}" 
                                         alt="{{ $category->name }}"
                                         class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-lg text-gray-800 mb-1">{{ $category->name }}</h3>
                                    <p class="text-gray-600 text-sm line-clamp-2">{{ $category->description ?? 'Explore our selection of ' . $category->name }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-8">
                    <a href="{{ route('storefront.products.index') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        View All Categories
                    </a>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Featured Products -->
    <div class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-8">Featured Products</h2>
            
            @if($featuredProducts->isEmpty())
                <div class="text-center p-8 bg-gray-50 rounded-lg">
                    <p class="text-gray-500">No featured products found.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($featuredProducts as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <a href="{{ route('storefront.products.show', $product->slug) }}" class="block">
                                <div class="h-48 bg-gray-100 overflow-hidden">
                                    <img src="{{ $product->image ?? 'https://via.placeholder.com/300x300?text=' . urlencode($product->name) }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover hover:opacity-90 transition-opacity">
                                </div>
                            </a>
                            <div class="p-4">
                                <a href="{{ route('storefront.products.show', $product->slug) }}" class="block">
                                    <h3 class="font-medium text-gray-900 mb-1">{{ $product->name }}</h3>
                                </a>
                                <p class="text-gray-500 text-sm mb-2 line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-900 font-bold">${{ number_format($product->price, 2) }}</span>
                                    <a href="{{ route('storefront.products.show', $product->slug) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-8">
                    <a href="{{ route('storefront.products.index') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        View All Products
                    </a>
                </div>
            @endif
        </div>
    </div>
    
    <!-- New Arrivals Section -->
    <div class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-8">New Arrivals</h2>
            
            @if($newArrivals->isEmpty())
                <div class="text-center p-8 bg-white rounded-lg">
                    <p class="text-gray-500">No new arrivals found.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($newArrivals as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="relative">
                                <a href="{{ route('storefront.products.show', $product->slug) }}" class="block">
                                    <div class="h-48 bg-gray-100 overflow-hidden">
                                        <img src="{{ $product->image ?? 'https://via.placeholder.com/300x300?text=' . urlencode($product->name) }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover hover:opacity-90 transition-opacity">
                                    </div>
                                </a>
                                <span class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded">New</span>
                            </div>
                            <div class="p-4">
                                <a href="{{ route('storefront.products.show', $product->slug) }}" class="block">
                                    <h3 class="font-medium text-gray-900 mb-1">{{ $product->name }}</h3>
                                </a>
                                <p class="text-gray-500 text-sm mb-2 line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-900 font-bold">${{ number_format($product->price, 2) }}</span>
                                    <a href="{{ route('storefront.products.show', $product->slug) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    
    <!-- Benefits Section -->
    <div class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="text-blue-600 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Quality Products</h3>
                    <p class="text-gray-600">We carefully select only the best products for our store.</p>
                </div>
                <div class="text-center p-6">
                    <div class="text-blue-600 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Fast Shipping</h3>
                    <p class="text-gray-600">Get your orders delivered quickly and reliably.</p>
                </div>
                <div class="text-center p-6">
                    <div class="text-blue-600 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Secure Payments</h3>
                    <p class="text-gray-600">Shop with confidence with our secure payment options.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Newsletter Section -->
    <div class="bg-blue-600 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-2xl md:text-3xl font-bold mb-4">Subscribe to Our Newsletter</h2>
                <p class="mb-6">Stay updated with the latest products, promotions, and exclusive offers.</p>
                <form class="flex flex-col sm:flex-row gap-2 justify-center">
                    <input type="email" placeholder="Your email address" class="px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-white text-gray-800 flex-grow max-w-md">
                    <button type="submit" class="px-6 py-2 bg-white text-blue-600 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection 