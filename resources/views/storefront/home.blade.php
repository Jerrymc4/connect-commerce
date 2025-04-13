@extends('layouts.storefront')

@section('title', $storeName . ' | Home')

@section('content')
<div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Welcome to {{ $storeName }}</h1>
            <p class="text-xl md:text-2xl mb-8">Discover amazing products at unbeatable prices.</p>
            <a href="{{ route('storefront.products') }}" class="inline-block bg-white text-purple-600 font-semibold px-6 py-3 rounded-lg shadow-md hover:bg-gray-100 transition duration-200">
                Shop Now
            </a>
        </div>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Featured Categories</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @for ($i = 1; $i <= 4; $i++)
            <div class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition duration-300">
                <div class="h-64 bg-gray-200 relative">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-gray-400"><i class="fas fa-image text-4xl"></i></span>
                    </div>
                </div>
                <div class="p-4 bg-white">
                    <h3 class="font-semibold text-lg">Category {{ $i }}</h3>
                    <p class="text-gray-600 text-sm">12 products</p>
                </div>
                <a href="#" class="absolute inset-0" aria-label="View Category {{ $i }}"></a>
            </div>
            @endfor
        </div>
    </div>
</div>

<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Featured Products</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @for ($i = 1; $i <= 8; $i++)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                <div class="h-48 bg-gray-200 relative">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-gray-400"><i class="fas fa-box text-4xl"></i></span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold">Product {{ $i }}</h3>
                    <p class="text-gray-600 text-sm mb-2">Lorem ipsum dolor sit amet</p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-lg">${{ rand(10, 100) }}.99</span>
                        <button class="bg-purple-600 text-white px-3 py-1 rounded-md text-sm hover:bg-purple-700">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        
        <div class="text-center mt-10">
            <a href="{{ route('storefront.products') }}" class="inline-block bg-purple-600 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:bg-purple-700 transition duration-200">
                View All Products
            </a>
        </div>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Why Choose Us</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="inline-block p-4 bg-purple-100 rounded-full mb-4">
                        <i class="fas fa-shipping-fast text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="font-semibold text-xl mb-2">Fast Shipping</h3>
                    <p class="text-gray-600">Free shipping on orders over $50</p>
                </div>
                
                <div class="text-center">
                    <div class="inline-block p-4 bg-purple-100 rounded-full mb-4">
                        <i class="fas fa-undo text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="font-semibold text-xl mb-2">Easy Returns</h3>
                    <p class="text-gray-600">30-day money back guarantee</p>
                </div>
                
                <div class="text-center">
                    <div class="inline-block p-4 bg-purple-100 rounded-full mb-4">
                        <i class="fas fa-headset text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="font-semibold text-xl mb-2">24/7 Support</h3>
                    <p class="text-gray-600">Our team is at your service</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-16 bg-purple-600 text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-6">Subscribe to Our Newsletter</h2>
            <p class="text-lg mb-8 text-purple-100">Get the latest updates on new products and upcoming sales</p>
            
            <form class="max-w-md mx-auto flex">
                <input type="email" placeholder="Your email address" class="flex-1 px-4 py-3 rounded-l-lg text-gray-900" required>
                <button type="submit" class="bg-pink-500 px-6 py-3 rounded-r-lg font-semibold hover:bg-pink-600 transition duration-200">
                    Subscribe
                </button>
            </form>
        </div>
    </div>
</div>
@endsection 