@extends('layouts.storefront')

@section('title', $storeName)

@section('content')
    <!-- Hero Banner -->
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
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @forelse($featuredCategories as $category)
                <a href="{{ route('storefront.categories.show', $category->slug) }}" class="block group">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden aspect-square relative">
                        @if($category->image)
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                <i class="fas fa-folder text-4xl text-gray-400"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                            <h3 class="text-white font-bold p-4 w-full">{{ $category->name }}</h3>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-4 text-center py-8">
                    <p class="text-gray-500">No categories found</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Featured Products -->
    <div class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-8">Featured Products</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($featuredProducts as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <a href="{{ route('storefront.products.show', $product->slug) }}" class="block aspect-square overflow-hidden bg-gray-100">
                        @if($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-4xl text-gray-300"></i>
                            </div>
                        @endif
                    </a>
                    <div class="p-4">
                        <a href="{{ route('storefront.products.show', $product->slug) }}" class="block">
                            <h3 class="font-medium text-gray-900 mb-2 hover:text-blue-600 transition-colors">{{ $product->name }}</h3>
                        </a>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-900 font-bold">${{ number_format($product->price, 2) }}</span>
                            
                            @if($product->compare_price > $product->price)
                                <span class="text-gray-500 line-through text-sm">${{ number_format($product->compare_price, 2) }}</span>
                            @endif
                        </div>
                        <div class="mt-4">
                            <form action="{{ route('storefront.cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition-colors">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-4 text-center py-8">
                    <p class="text-gray-500">No featured products found</p>
                </div>
                @endforelse
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('storefront.products.index') }}" class="btn btn-outline-primary mt-3">View All Products</a>
            </div>
        </div>
    </div>
    
    <!-- New Arrivals -->
    <div class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-8">New Arrivals</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($newArrivals as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <a href="{{ route('storefront.products.show', $product->slug) }}" class="block aspect-square overflow-hidden bg-gray-100">
                        @if($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-4xl text-gray-300"></i>
                            </div>
                        @endif
                    </a>
                    <div class="p-4">
                        <a href="{{ route('storefront.products.show', $product->slug) }}" class="block">
                            <h3 class="font-medium text-gray-900 mb-2 hover:text-blue-600 transition-colors">{{ $product->name }}</h3>
                        </a>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-900 font-bold">${{ number_format($product->price, 2) }}</span>
                            
                            @if($product->compare_price > $product->price)
                                <span class="text-gray-500 line-through text-sm">${{ number_format($product->compare_price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-4 text-center py-8">
                    <p class="text-gray-500">No new products found</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Company Benefits -->
    <div class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                <div class="p-6">
                    <div class="text-blue-600 text-3xl mb-4">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Free Shipping</h3>
                    <p class="text-gray-600">On all orders over $75</p>
                </div>
                
                <div class="p-6">
                    <div class="text-blue-600 text-3xl mb-4">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Easy Returns</h3>
                    <p class="text-gray-600">30-day return policy</p>
                </div>
                
                <div class="p-6">
                    <div class="text-blue-600 text-3xl mb-4">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Secure Payments</h3>
                    <p class="text-gray-600">Protected by encryption</p>
                </div>
                
                <div class="p-6">
                    <div class="text-blue-600 text-3xl mb-4">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">24/7 Support</h3>
                    <p class="text-gray-600">Contact us anytime</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Newsletter Signup -->
    <div class="py-12 bg-gray-800 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">Subscribe to Our Newsletter</h2>
            <p class="text-gray-300 mb-6 max-w-xl mx-auto">Stay updated with the latest products, special offers, and news</p>
            
            <form class="max-w-md mx-auto flex">
                <input type="email" placeholder="Your email address" class="flex-grow px-4 py-2 rounded-l-lg focus:outline-none text-gray-900">
                <button type="submit" class="bg-blue-600 px-6 py-2 rounded-r-lg hover:bg-blue-700 transition-colors">
                    Subscribe
                </button>
            </form>
        </div>
    </div>
@endsection 