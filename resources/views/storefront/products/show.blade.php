@extends('layouts.storefront')

@section('title', $product->name)

@section('content')
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                <li class="inline-flex items-center">
                    <a href="{{ route('storefront.home') }}" class="text-gray-600 hover:text-blue-600">
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('storefront.products.index') }}" class="ml-1 text-gray-600 hover:text-blue-600 md:ml-2">
                            Products
                        </a>
                    </div>
                </li>
                @if($product->categories->isNotEmpty())
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('storefront.products.index', ['category' => $product->categories->first()->id]) }}" class="ml-1 text-gray-600 hover:text-blue-600 md:ml-2">
                                {{ $product->categories->first()->name }}
                            </a>
                        </div>
                    </li>
                @endif
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500 md:ml-2 font-medium">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Product Detail -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="md:flex">
                <!-- Product Image -->
                <div class="md:w-1/2">
                    <div class="bg-gray-100 p-6 flex items-center justify-center">
                        <img 
                            src="{{ $product->image ?? 'https://via.placeholder.com/600x600?text=' . urlencode($product->name) }}" 
                            alt="{{ $product->name }}" 
                            class="w-full h-auto max-h-[600px] object-contain"
                        >
                    </div>
                </div>

                <!-- Product Info -->
                <div class="md:w-1/2 p-6 md:p-8">
                    <div class="mb-6">
                        @if($product->categories->isNotEmpty())
                            <div class="text-sm text-blue-600 mb-2">
                                {{ $product->categories->first()->name }}
                            </div>
                        @endif
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                        
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                            <span class="text-gray-500 ml-2 text-sm">(20 reviews)</span>
                        </div>
                        
                        <div class="mb-6">
                            <div class="flex items-center">
                                <span class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                @if(isset($product->compare_price) && $product->compare_price > $product->price)
                                    <span class="text-gray-500 line-through text-lg ml-3">${{ number_format($product->compare_price, 2) }}</span>
                                    <span class="ml-3 bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">
                                        {{ round((1 - $product->price / $product->compare_price) * 100) }}% OFF
                                    </span>
                                @endif
                            </div>
                            @if(isset($product->stock) && $product->stock > 0)
                                <div class="mt-2">
                                    <span class="text-green-600 text-sm flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        In Stock ({{ $product->stock }})
                                    </span>
                                </div>
                            @else
                                <div class="mt-2">
                                    <span class="text-gray-500 text-sm flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Out of Stock
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-900 mb-2">Description</h3>
                        <div class="text-gray-600 space-y-2">
                            <p>{{ $product->description }}</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('storefront.cart.add') }}" method="POST" class="mb-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <!-- Quantity Selector -->
                        <div class="mb-6">
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                            <div class="flex">
                                <button type="button" id="decreaseQuantity" class="bg-gray-100 px-3 py-2 border border-gray-300 rounded-l-md text-gray-600 hover:bg-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                </button>
                                <input 
                                    type="number" 
                                    id="quantity" 
                                    name="quantity" 
                                    value="1" 
                                    min="1" 
                                    max="{{ $product->stock ?? 10 }}" 
                                    class="w-16 text-center border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                >
                                <button type="button" id="increaseQuantity" class="bg-gray-100 px-3 py-2 border border-gray-300 rounded-r-md text-gray-600 hover:bg-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Add to Cart Button -->
                        <div class="flex space-x-4">
                            <button type="submit" class="flex-1 bg-blue-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Add to Cart
                            </button>
                            <button type="button" class="flex items-center justify-center p-3 text-gray-400 hover:text-red-500 bg-gray-100 rounded-md hover:bg-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Product Details -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="font-medium text-gray-900 mb-4">Product Details</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            @if(isset($product->sku))
                                <div>
                                    <span class="text-gray-500">SKU:</span>
                                    <span class="ml-2 text-gray-900">{{ $product->sku }}</span>
                                </div>
                            @endif
                            
                            @if(isset($product->weight))
                                <div>
                                    <span class="text-gray-500">Weight:</span>
                                    <span class="ml-2 text-gray-900">{{ $product->weight }} kg</span>
                                </div>
                            @endif
                            
                            @if(isset($product->dimensions))
                                <div>
                                    <span class="text-gray-500">Dimensions:</span>
                                    <span class="ml-2 text-gray-900">{{ $product->dimensions }}</span>
                                </div>
                            @endif
                            
                            @if($product->categories->isNotEmpty())
                                <div>
                                    <span class="text-gray-500">Categories:</span>
                                    <span class="ml-2 text-gray-900">
                                        @foreach($product->categories as $category)
                                            <a href="{{ route('storefront.products.index', ['category' => $category->id]) }}" class="text-blue-600 hover:underline">
                                                {{ $category->name }}
                                            </a>
                                            @if(!$loop->last), @endif
                                        @endforeach
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="mt-10" x-data="{ activeTab: 'description' }">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button @click="activeTab = 'description'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'description', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'description' }" class="py-4 px-1 border-b-2 font-medium text-sm">
                        Description
                    </button>
                    <button @click="activeTab = 'specifications'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'specifications', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'specifications' }" class="py-4 px-1 border-b-2 font-medium text-sm">
                        Specifications
                    </button>
                    <button @click="activeTab = 'reviews'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'reviews', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'reviews' }" class="py-4 px-1 border-b-2 font-medium text-sm">
                        Reviews
                    </button>
                </nav>
            </div>
            
            <div class="py-6">
                <div x-show="activeTab === 'description'" class="prose max-w-none">
                    <p>{{ $product->description }}</p>
                </div>
                
                <div x-show="activeTab === 'specifications'" class="hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="border rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b">
                                <h3 class="text-sm font-medium text-gray-900">Product Specifications</h3>
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                    @if(isset($product->sku))
                                        <div class="sm:col-span-1">
                                            <dt class="text-sm font-medium text-gray-500">SKU</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $product->sku }}</dd>
                                        </div>
                                    @endif
                                    
                                    @if(isset($product->weight))
                                        <div class="sm:col-span-1">
                                            <dt class="text-sm font-medium text-gray-500">Weight</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $product->weight }} kg</dd>
                                        </div>
                                    @endif
                                    
                                    @if(isset($product->dimensions))
                                        <div class="sm:col-span-1">
                                            <dt class="text-sm font-medium text-gray-500">Dimensions</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $product->dimensions }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        </div>
                        
                        <div class="border rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b">
                                <h3 class="text-sm font-medium text-gray-900">Shipping Information</h3>
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Shipping Time</dt>
                                        <dd class="mt-1 text-sm text-gray-900">2-5 business days</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Return Policy</dt>
                                        <dd class="mt-1 text-sm text-gray-900">30 days</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div x-show="activeTab === 'reviews'" class="hidden">
                    <div class="flow-root">
                        <div class="text-center py-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <h3 class="text-sm font-medium text-gray-900 mb-2">No reviews yet</h3>
                            <p class="text-sm text-gray-500 mb-6">Be the first to review this product</p>
                            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Write a review
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        @if($relatedProducts->isNotEmpty())
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden group hover:shadow-md transition-shadow duration-300">
                            <a href="{{ route('storefront.products.show', $relatedProduct->slug) }}" class="block">
                                <div class="aspect-square bg-gray-100 overflow-hidden">
                                    <img 
                                        src="{{ $relatedProduct->image ?? 'https://via.placeholder.com/300x300?text=' . urlencode($relatedProduct->name) }}" 
                                        alt="{{ $relatedProduct->name }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    >
                                </div>
                            </a>
                            <div class="p-4">
                                <a href="{{ route('storefront.products.show', $relatedProduct->slug) }}" class="block">
                                    <h3 class="text-gray-900 font-medium mb-1 group-hover:text-blue-600 transition-colors">{{ $relatedProduct->name }}</h3>
                                </a>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-gray-900 font-bold">${{ number_format($relatedProduct->price, 2) }}</span>
                                    <a href="{{ route('storefront.products.show', $relatedProduct->slug) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity selector
        const quantityInput = document.getElementById('quantity');
        const decreaseBtn = document.getElementById('decreaseQuantity');
        const increaseBtn = document.getElementById('increaseQuantity');
        
        if (quantityInput && decreaseBtn && increaseBtn) {
            decreaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });
            
            increaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value);
                const maxValue = parseInt(quantityInput.getAttribute('max'));
                if (currentValue < maxValue) {
                    quantityInput.value = currentValue + 1;
                }
            });
        }
    });
</script>
@endpush
@endsection 