@extends('layouts.storefront')

@section('title', $storeName)

@section('content')
    <div class="min-h-screen">
        <!-- Hero Section -->
        <div class="relative" style="background-color: {{ $themeSettings['banner_bg_color'] ?? '#4F46E5' }};">
            <div class="max-w-7xl mx-auto py-24 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-extrabold sm:text-5xl md:text-6xl" style="color: {{ $themeSettings['banner_text_color'] ?? '#FFFFFF' }};">
                        {{ $themeSettings['banner_title'] ?? $storeName }}
                    </h1>
                    <p class="mt-3 max-w-md mx-auto text-base sm:text-lg md:mt-5 md:text-xl md:max-w-3xl" style="color: {{ $themeSettings['banner_text_color'] ?? '#FFFFFF' }};">
                        {{ $themeSettings['banner_subtitle'] ?? 'Discover amazing products at great prices' }}
                    </p>
                    @if(isset($themeSettings['banner_cta_text']) && !empty($themeSettings['banner_cta_text']))
                    <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                        <div class="rounded-md shadow">
                            <a href="{{ $themeSettings['banner_cta_url'] ?? '/products' }}" 
                               class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md md:py-4 md:text-lg md:px-10"
                               style="background-color: {{ $themeSettings['banner_cta_bg_color'] ?? '#FFFFFF' }}; color: {{ $themeSettings['banner_cta_text_color'] ?? '#4F46E5' }};">
                                {{ $themeSettings['banner_cta_text'] }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Featured Categories -->
        <div class="py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-8 text-primary">Shop by Category</h2>
                
                @if($featuredCategories->isEmpty())
                    <div class="text-center p-8 bg-white rounded-lg shadow">
                        <p class="text-muted">No categories found.</p>
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
                                        <h3 class="font-bold text-lg text-primary mb-1">{{ $category->name }}</h3>
                                        <p class="text-secondary text-sm line-clamp-2">{{ $category->description ?? 'Explore our selection of ' . $category->name }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="text-center mt-8">
                        <a href="{{ route('storefront.products.index') }}" class="inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark transition-colors">
                            View All Categories
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Featured Products -->
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-extrabold text-primary mb-8">Featured Products</h2>
            <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                @foreach($featuredProducts as $product)
                    <div class="group relative card shadow-color">
                        <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75">
                            @if($product->images && $product->images->isNotEmpty())
                                <img src="{{ $product->images->first()->url }}" alt="{{ $product->name }}" class="w-full h-full object-center object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <span class="text-muted">No image</span>
                                </div>
                            @endif
                        </div>
                        <div class="mt-4 p-4">
                            <h3 class="text-sm text-primary">
                                <a href="{{ route('storefront.products.show', $product) }}">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="mt-1 text-sm text-secondary">{{ $product->description }}</p>
                            <p class="mt-1 text-sm font-medium text-primary">${{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- New Arrivals Section -->
        <div class="py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-8 text-primary">New Arrivals</h2>
                
                @if($newArrivals->isEmpty())
                    <div class="text-center p-8 bg-white rounded-lg">
                        <p class="text-muted">No new arrivals found.</p>
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
                                    <span class="absolute top-2 right-2 bg-success text-white text-xs px-2 py-1 rounded">New</span>
                                </div>
                                <div class="p-4">
                                    <a href="{{ route('storefront.products.show', $product->slug) }}" class="block">
                                        <h3 class="font-medium text-primary mb-1">{{ $product->name }}</h3>
                                    </a>
                                    <p class="text-secondary text-sm mb-2 line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-primary font-bold">${{ number_format($product->price, 2) }}</span>
                                        <a href="{{ route('storefront.products.show', $product->slug) }}" 
                                           class="text-primary hover:text-primary-dark text-sm font-medium">
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
                        <div class="text-primary mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-primary">Quality Products</h3>
                        <p class="text-secondary">We carefully select only the best products for our store.</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="text-primary mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-primary">Fast Shipping</h3>
                        <p class="text-secondary">Get your orders delivered quickly and reliably.</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="text-primary mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-primary">Secure Payments</h3>
                        <p class="text-secondary">Shop with confidence with our secure payment options.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Newsletter Section -->
        <div class="bg-primary text-white py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-2xl mx-auto text-center">
                    <h2 class="text-2xl md:text-3xl font-bold mb-4">Subscribe to Our Newsletter</h2>
                    <p class="mb-6">Stay updated with the latest products, promotions, and exclusive offers.</p>
                    <form class="flex flex-col sm:flex-row gap-2 justify-center">
                        <input type="email" placeholder="Your email address" class="px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-white text-gray-800 flex-grow max-w-md">
                        <button type="submit" class="px-6 py-2 bg-white text-primary rounded-lg font-medium hover:bg-gray-100 transition-colors">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 