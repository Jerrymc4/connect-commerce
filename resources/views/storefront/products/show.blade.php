@extends('layouts.storefront')

@section('title', $product->name)

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
            <!-- Image gallery -->
            <div class="flex flex-col space-y-4">
                <div class="aspect-w-1 aspect-h-1 w-full">
                    <div class="relative h-96 w-full overflow-hidden rounded-lg bg-gray-100">
                        @if($product->images->isNotEmpty())
                            <img src="{{ $product->images->first()->url }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center">
                        @else
                            <div class="h-full w-full flex items-center justify-center">
                                <span class="text-muted">No image available</span>
                            </div>
                        @endif
                    </div>
                </div>
                @if($product->images->count() > 1)
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($product->images as $image)
                            <div class="aspect-w-1 aspect-h-1 w-full">
                                <div class="relative h-24 w-full overflow-hidden rounded-lg bg-gray-100">
                                    <img src="{{ $image->url }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product info -->
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <h1 class="text-3xl font-extrabold tracking-tight text-primary">{{ $product->name }}</h1>

                <div class="mt-3">
                    <h2 class="sr-only">Product information</h2>
                    <p class="text-3xl text-primary">${{ number_format($product->price, 2) }}</p>
                    
                    <!-- Limited time offer countdown -->
                    @if(isset($product->sale_ends_at) && $product->sale_ends_at > now())
                    <div class="mt-2">
                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-red-100 text-red-800">
                            <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            Special offer ends in: <span id="countdown-timer" class="ml-1 font-bold"></span>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">Description</h3>
                    <div class="text-base text-secondary space-y-6">
                        {{ $product->description }}
                    </div>
                </div>

                <!-- Product tabs: Description, Specification, Size Guide -->
                <div class="mt-8 border-t border-border">
                    <div class="product-tabs border-b border-border">
                        <div class="flex space-x-8" role="tablist" aria-orientation="horizontal">
                            <button id="tab-description" class="py-4 px-1 text-sm font-medium text-primary border-b-2 border-button whitespace-nowrap" role="tab" aria-controls="description-panel" aria-selected="true">
                                Description
                            </button>
                            <button id="tab-specs" class="py-4 px-1 text-sm font-medium text-gray-500 border-b-2 border-transparent whitespace-nowrap" role="tab" aria-controls="specs-panel" aria-selected="false">
                                Specifications
                            </button>
                            <button id="tab-size" class="py-4 px-1 text-sm font-medium text-gray-500 border-b-2 border-transparent whitespace-nowrap" role="tab" aria-controls="size-panel" aria-selected="false">
                                Size Guide
                            </button>
                        </div>
                    </div>
                    
                    <!-- Description tab panel -->
                    <div id="description-panel" class="py-6" role="tabpanel" aria-labelledby="tab-description">
                        <div class="prose prose-sm text-secondary">
                            <p>{{ $product->description }}</p>
                        </div>
                    </div>
                    
                    <!-- Specifications tab panel -->
                    <div id="specs-panel" class="py-6 hidden" role="tabpanel" aria-labelledby="tab-specs">
                        <div class="prose prose-sm text-secondary">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @if(isset($product->specifications) && is_array($product->specifications))
                                    @foreach($product->specifications as $spec => $value)
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="font-medium">{{ $spec }}</span>
                                            <span>{{ $value }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-span-2">
                                        <p>Specifications for this product are not available.</p>
                                        
                                        <!-- Sample specs for demonstration -->
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="font-medium">Material</span>
                                            <span>Premium cotton</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="font-medium">Weight</span>
                                            <span>0.5 kg</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="font-medium">Dimensions</span>
                                            <span>30 x 20 x 10 cm</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-gray-100">
                                            <span class="font-medium">Origin</span>
                                            <span>USA</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Size Guide tab panel -->
                    <div id="size-panel" class="py-6 hidden" role="tabpanel" aria-labelledby="tab-size">
                        <div class="prose prose-sm text-secondary">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chest (in)</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waist (in)</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hip (in)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">XS</td>
                                        <td class="px-4 py-3 whitespace-nowrap">34-36</td>
                                        <td class="px-4 py-3 whitespace-nowrap">28-30</td>
                                        <td class="px-4 py-3 whitespace-nowrap">34-36</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">S</td>
                                        <td class="px-4 py-3 whitespace-nowrap">36-38</td>
                                        <td class="px-4 py-3 whitespace-nowrap">30-32</td>
                                        <td class="px-4 py-3 whitespace-nowrap">36-38</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">M</td>
                                        <td class="px-4 py-3 whitespace-nowrap">38-40</td>
                                        <td class="px-4 py-3 whitespace-nowrap">32-34</td>
                                        <td class="px-4 py-3 whitespace-nowrap">38-40</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">L</td>
                                        <td class="px-4 py-3 whitespace-nowrap">40-42</td>
                                        <td class="px-4 py-3 whitespace-nowrap">34-36</td>
                                        <td class="px-4 py-3 whitespace-nowrap">40-42</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">XL</td>
                                        <td class="px-4 py-3 whitespace-nowrap">42-44</td>
                                        <td class="px-4 py-3 whitespace-nowrap">36-38</td>
                                        <td class="px-4 py-3 whitespace-nowrap">42-44</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="mt-4 text-sm text-gray-500">These are general guidelines. For the best fit, we recommend taking your own measurements and comparing them to the size chart.</p>
                        </div>
                    </div>
                </div>

                @if($themeSettings['enable_rating_system'] ?? true)
                <!-- Ratings Section -->
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-primary mb-2">Customer Ratings</h3>
                    <div class="flex items-center">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= ($product->average_rating ?? 0))
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <p class="ml-2 text-sm text-secondary">{{ number_format($product->average_rating ?? 0, 1) }} out of 5 ({{ $product->reviews_count ?? 0 }} reviews)</p>
                        @if(($product->reviews_count ?? 0) > 0)
                            <a href="{{ route('storefront.products.reviews', $product) }}" class="ml-4 text-sm font-medium text-button hover:text-button-hover">
                                View all reviews
                            </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Reviews Section -->
                @if(($themeSettings['enable_reviews'] ?? true) && isset($product->reviews) && $product->reviews->isNotEmpty())
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-primary">Recent Reviews</h3>
                        @if($product->reviews->count() > 3)
                        <a href="{{ route('storefront.products.reviews', $product) }}" class="text-sm font-medium text-button hover:text-button-hover">
                            View all {{ $product->reviews->count() }} reviews
                        </a>
                        @endif
                    </div>
                    <div class="space-y-6">
                        @foreach($product->reviews->take(3) as $review)
                        <div class="border-b border-gray-200 pb-4">
                            <div class="flex items-center">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <p class="ml-2 text-xs text-secondary">{{ $review->created_at->format('M d, Y') }}</p>
                            </div>
                            <p class="mt-1 text-sm text-secondary">{{ $review->review }}</p>
                            <p class="mt-1 text-xs text-muted">By {{ $review->customer->name ?? 'Anonymous' }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Write a Review Button -->
                @if(($themeSettings['enable_reviews'] ?? true))
                <div class="mt-6">
                    <button id="writeReviewButton" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-button hover:bg-button-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button">
                        Write a Review
                    </button>
                
                    <div id="reviewForm" class="mt-4 hidden">
                        <form action="{{ route('storefront.products.review', $product) }}" method="POST" class="space-y-4 bg-gray-50 p-4 rounded-lg">
                            @csrf
                            <div>
                                <label for="rating" class="block text-sm font-medium text-primary">Your Rating</label>
                                <div class="flex items-center mt-1">
                                    <div class="flex rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" data-rating="{{ $i }}" class="rating-star text-gray-300 focus:outline-none">
                                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>
                                </div>
                                <input type="hidden" name="rating" id="rating" value="0">
                            </div>
                            <div>
                                <label for="review" class="block text-sm font-medium text-primary">Your Review</label>
                                <textarea id="review" name="review" rows="4" class="mt-1 block w-full border border-border-color rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-accent focus:border-accent"></textarea>
                            </div>
                            <div>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-button hover:bg-button-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button">
                                    Submit Review
                                </button>
                                <button type="button" id="cancelReview" class="ml-2 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                <form class="mt-6" action="{{ route('storefront.cart.add') }}" method="POST" id="addToCartForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="mt-4">
                        <label for="quantity" class="block text-sm font-medium text-primary">Quantity</label>
                        <div class="custom-number-input h-10 w-32 mt-1">
                            <div class="flex flex-row h-10 w-full rounded-lg relative bg-transparent">
                                <button type="button" id="decreaseQuantity" class="bg-gray-100 text-gray-600 hover:text-gray-700 hover:bg-gray-200 h-full w-10 rounded-l cursor-pointer outline-none flex items-center justify-center">
                                    <span class="m-auto text-xl font-semibold">−</span>
                                </button>
                                <input type="number" id="quantity" name="quantity" min="1" max="{{ $product->stock_quantity ?? 99 }}" value="1" class="outline-none focus:outline-none text-center w-full bg-gray-100 font-semibold text-md hover:text-black focus:text-black md:text-base cursor-default flex items-center text-gray-700">
                                <button type="button" id="increaseQuantity" class="bg-gray-100 text-gray-600 hover:text-gray-700 hover:bg-gray-200 h-full w-10 rounded-r cursor-pointer flex items-center justify-center">
                                    <span class="m-auto text-xl font-semibold">+</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex sm:flex-col1">
                        <button type="submit" class="max-w-xs flex-1 bg-button text-white border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium hover:bg-button-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-button">
                            Add to cart
                        </button>

                        <button type="button" id="addToWishlist" data-product-id="{{ $product->id }}" class="ml-4 py-3 px-3 rounded-md flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-500">
                            <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="sr-only">Add to wishlist</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 border-t border-border pt-6">
                    <h3 class="text-sm font-medium text-primary">Highlights</h3>
                    <div class="mt-4 prose prose-sm text-secondary">
                        <ul class="list-disc pl-5 space-y-2">
                            @foreach($product->highlights as $highlight)
                                <li>{{ $highlight }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Product sharing -->
                <div class="mt-6 border-t border-border pt-6">
                    <h3 class="text-sm font-medium text-primary">Share this product</h3>
                    <div class="mt-2 flex space-x-4">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="text-gray-400 hover:text-[#3b5998]">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($product->name) }}&url={{ urlencode(request()->url()) }}" target="_blank" class="text-gray-400 hover:text-[#1DA1F2]">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                        <a href="mailto:?subject={{ urlencode('Check out this product: ' . $product->name) }}&body={{ urlencode('I thought you might like this: ' . request()->url()) }}" class="text-gray-400 hover:text-gray-700">
                            <span class="sr-only">Email</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Estimated delivery -->
                <div class="mt-6 border-t border-border pt-6">
                    <h3 class="text-sm font-medium text-primary">Delivery Information</h3>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1v-5h2.038A2.968 2.968 0 0115 8.506V7H5.04A1.04 1.04 0 014 5.96V4.15a1 1 0 00-1-1.15z" />
                            </svg>
                            <span class="text-sm text-secondary">Free shipping on orders over $50</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm text-secondary">Estimated delivery: 3-5 business days</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm text-secondary">
                                @if($product->stock_quantity > 10)
                                    <span class="text-green-600 font-medium">In Stock</span>
                                @elseif($product->stock_quantity > 0)
                                    <span class="text-yellow-600 font-medium">Only {{ $product->stock_quantity }} left in stock</span>
                                @else
                                    <span class="text-red-600 font-medium">Out of Stock</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related products -->
        @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <div class="mt-16">
            <h2 class="text-2xl font-extrabold tracking-tight text-primary">Customers also purchased</h2>
            <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                @foreach($relatedProducts as $relatedProduct)
                <div class="group relative">
                    <div class="w-full min-h-80 bg-gray-100 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75">
                        @if($relatedProduct->images->isNotEmpty())
                            <img src="{{ $relatedProduct->images->first()->url }}" alt="{{ $relatedProduct->name }}" class="w-full h-full object-center object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center">
                                <span class="text-muted">No image available</span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-primary">
                                <a href="{{ route('storefront.products.show', $relatedProduct) }}">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ $relatedProduct->name }}
                                </a>
                            </h3>
                        </div>
                        <p class="text-sm font-medium text-primary">${{ number_format($relatedProduct->price, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recently viewed products -->
        @if(isset($recentlyViewed) && $recentlyViewed->isNotEmpty())
        <div class="mt-16">
            <h2 class="text-2xl font-extrabold tracking-tight text-primary">Recently viewed</h2>
            <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                @foreach($recentlyViewed as $viewedProduct)
                <div class="group relative">
                    <div class="w-full min-h-80 bg-gray-100 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75">
                        @if($viewedProduct->images->isNotEmpty())
                            <img src="{{ $viewedProduct->images->first()->url }}" alt="{{ $viewedProduct->name }}" class="w-full h-full object-center object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center">
                                <span class="text-muted">No image available</span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-primary">
                                <a href="{{ route('storefront.products.show', $viewedProduct) }}">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ $viewedProduct->name }}
                                </a>
                            </h3>
                        </div>
                        <p class="text-sm font-medium text-primary">${{ number_format($viewedProduct->price, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Customers also viewed section -->
        @if(isset($alsoViewedProducts) && $alsoViewedProducts->isNotEmpty())
        <div class="mt-16">
            <h2 class="text-2xl font-extrabold tracking-tight text-primary">Customers also viewed</h2>
            <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                @foreach($alsoViewedProducts as $alsoViewedProduct)
                <div class="group relative">
                    <div class="w-full min-h-80 bg-gray-100 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75">
                        @if($alsoViewedProduct->images->isNotEmpty())
                            <img src="{{ $alsoViewedProduct->images->first()->url }}" alt="{{ $alsoViewedProduct->name }}" class="w-full h-full object-center object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center">
                                <span class="text-muted">No image available</span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-primary">
                                <a href="{{ route('storefront.products.show', $alsoViewedProduct) }}">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ $alsoViewedProduct->name }}
                                </a>
                            </h3>
                        </div>
                        <p class="text-sm font-medium text-primary">${{ number_format($alsoViewedProduct->price, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Sticky Add to Cart Bar for Mobile -->
<div id="stickyAddToCart" class="fixed bottom-0 left-0 right-0 bg-white shadow-lg border-t border-gray-200 z-50 p-4 transform translate-y-full transition-transform duration-300 sm:hidden">
    <div class="flex items-center justify-between">
        <div class="flex-shrink-0">
            <span class="text-lg font-bold text-primary">${{ number_format($product->price, 2) }}</span>
        </div>
        <div class="flex-grow mx-3">
            <div class="flex items-center">
                <button type="button" id="mobileDecreaseQuantity" class="bg-gray-100 text-gray-600 hover:text-gray-700 hover:bg-gray-200 h-8 w-8 rounded-l flex items-center justify-center">
                    <span class="m-auto text-base font-semibold">−</span>
                </button>
                <input type="number" id="mobileQuantity" min="1" max="{{ $product->stock_quantity ?? 99 }}" value="1" class="outline-none focus:outline-none text-center w-12 h-8 bg-gray-100 font-semibold text-md cursor-default flex items-center text-gray-700" readonly>
                <button type="button" id="mobileIncreaseQuantity" class="bg-gray-100 text-gray-600 hover:text-gray-700 hover:bg-gray-200 h-8 w-8 rounded-r flex items-center justify-center">
                    <span class="m-auto text-base font-semibold">+</span>
                </button>
            </div>
        </div>
        <div class="flex-shrink-0">
            <button type="button" id="mobileAddToCart" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-button hover:bg-button-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button">
                Add to cart
            </button>
        </div>
    </div>
</div>

<!-- Cart notification toast -->
<div id="cartNotification" class="fixed top-4 right-4 bg-green-50 border-l-4 border-green-400 p-4 shadow-md rounded-r-md z-50 transform translate-x-full transition-transform duration-300">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-green-700">Added to cart successfully!</p>
            <div class="mt-2 flex space-x-3">
                <a href="{{ route('storefront.cart') }}" class="text-sm font-medium text-green-700 hover:text-green-600">View cart</a>
                <button type="button" id="closeNotification" class="text-sm font-medium text-green-700 hover:text-green-600">
                    Dismiss
                </button>
            </div>
        </div>
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
            
            // Add input validation for direct number entry
            quantityInput.addEventListener('change', function() {
                const min = parseInt(this.getAttribute('min') || 1);
                const max = parseInt(this.getAttribute('max'));
                const value = parseInt(this.value);
                
                if (isNaN(value) || value < min) {
                    this.value = min;
                } else if (max && value > max) {
                    this.value = max;
                }
            });
        }

        // Rating system
        const writeReviewBtn = document.getElementById('writeReviewButton');
        const reviewForm = document.getElementById('reviewForm');
        const cancelReviewBtn = document.getElementById('cancelReview');
        const ratingStars = document.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('rating');
        
        if (writeReviewBtn && reviewForm) {
            writeReviewBtn.addEventListener('click', function() {
                reviewForm.classList.remove('hidden');
                writeReviewBtn.classList.add('hidden');
            });
        }
        
        if (cancelReviewBtn && reviewForm && writeReviewBtn) {
            cancelReviewBtn.addEventListener('click', function() {
                reviewForm.classList.add('hidden');
                writeReviewBtn.classList.remove('hidden');
            });
        }
        
        if (ratingStars.length > 0 && ratingInput) {
            ratingStars.forEach(star => {
                star.addEventListener('mouseover', function() {
                    const rating = parseInt(this.dataset.rating);
                    highlightStars(rating);
                });
                
                star.addEventListener('mouseout', function() {
                    const currentRating = parseInt(ratingInput.value);
                    highlightStars(currentRating);
                });
                
                star.addEventListener('click', function() {
                    const rating = parseInt(this.dataset.rating);
                    ratingInput.value = rating;
                    highlightStars(rating);
                });
            });
            
            function highlightStars(rating) {
                ratingStars.forEach(star => {
                    const starRating = parseInt(star.dataset.rating);
                    if (starRating <= rating) {
                        star.classList.remove('text-gray-300');
                        star.classList.add('text-yellow-400');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            }
        }

        // Image zoom functionality
        const productImages = document.querySelectorAll('.aspect-w-1.aspect-h-1 img');
        const mainImage = document.querySelector('.aspect-w-1.aspect-h-1:first-child img');
        
        if (productImages.length > 1 && mainImage) {
            productImages.forEach(img => {
                img.addEventListener('click', function() {
                    if (this !== mainImage) {
                        mainImage.src = this.src;
                        
                        // Add subtle animation
                        mainImage.style.opacity = '0';
                        setTimeout(() => {
                            mainImage.style.opacity = '1';
                            mainImage.style.transition = 'opacity 0.3s ease-in-out';
                        }, 100);
                    }
                });
            });
        }

        // Add to wishlist functionality
        const addToWishlistBtn = document.getElementById('addToWishlist');
        
        if (addToWishlistBtn) {
            addToWishlistBtn.addEventListener('click', function() {
                // Visual feedback
                this.classList.add('text-red-500');
                this.querySelector('svg').classList.add('fill-red-500');
                
                // You would typically make an AJAX call here to add to wishlist
                const productId = this.dataset.productId;
                
                // Show notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-50 border-l-4 border-green-400 p-4 z-50';
                notification.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">Added to wishlist</p>
                        </div>
                    </div>
                `;
                document.body.appendChild(notification);
                
                // Remove notification after 3 seconds
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 500);
                }, 3000);
            });
        }

        // Enhanced image zoom functionality
        const mainImageContainer = document.querySelector('.aspect-w-1.aspect-h-1:first-child');
        
        if (mainImageContainer) {
            // Add zoom class
            mainImageContainer.classList.add('image-zoom-container');
            
            // Create lens element
            const lens = document.createElement('div');
            lens.classList.add('image-zoom-lens');
            mainImageContainer.appendChild(lens);
            
            // Get image element
            const img = mainImageContainer.querySelector('img');
            
            if (img) {
                // Calculate ratio
                function getCursorPos(e) {
                    let a, x = 0, y = 0;
                    e = e || window.event;
                    a = img.getBoundingClientRect();
                    x = e.pageX - a.left - window.pageXOffset;
                    y = e.pageY - a.top - window.pageYOffset;
                    return {x, y};
                }
                
                // Mouse events
                mainImageContainer.addEventListener('mousemove', function(e) {
                    let pos = getCursorPos(e);
                    let x = pos.x;
                    let y = pos.y;
                    
                    // Prevent lens from being positioned outside image
                    if (x > img.width) x = img.width;
                    if (x < 0) x = 0;
                    if (y > img.height) y = img.height;
                    if (y < 0) y = 0;
                    
                    // Set lens position
                    lens.style.left = x - (lens.offsetWidth / 2) + "px";
                    lens.style.top = y - (lens.offsetHeight / 2) + "px";
                    
                    // Set background position
                    lens.style.backgroundImage = "url('" + img.src + "')";
                    lens.style.backgroundSize = (img.width * 3) + "px " + (img.height * 3) + "px";
                    lens.style.backgroundPosition = "-" + ((x * 3) - (lens.offsetWidth / 2)) + "px -" + 
                                                    ((y * 3) - (lens.offsetHeight / 2)) + "px";
                });
                
                mainImageContainer.addEventListener('mouseenter', function() {
                    lens.style.display = "block";
                });
                
                mainImageContainer.addEventListener('mouseleave', function() {
                    lens.style.display = "none";
                });
            }
        }

        // Product tabs functionality
        const tabButtons = document.querySelectorAll('[role="tab"]');
        
        if (tabButtons.length) {
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Deactivate all tabs
                    tabButtons.forEach(btn => {
                        btn.setAttribute('aria-selected', 'false');
                        btn.classList.remove('text-primary', 'border-button');
                        btn.classList.add('text-gray-500', 'border-transparent');
                    });
                    
                    // Activate clicked tab
                    button.setAttribute('aria-selected', 'true');
                    button.classList.remove('text-gray-500', 'border-transparent');
                    button.classList.add('text-primary', 'border-button');
                    
                    // Hide all panels
                    const panels = document.querySelectorAll('[role="tabpanel"]');
                    panels.forEach(panel => {
                        panel.classList.add('hidden');
                    });
                    
                    // Show selected panel
                    const panelId = button.getAttribute('aria-controls');
                    const panel = document.getElementById(panelId);
                    if (panel) {
                        panel.classList.remove('hidden');
                    }
                });
            });
        }

        // Countdown timer
        function initializeCountdown() {
            const countdownElement = document.getElementById('countdown-timer');
            
            if (countdownElement) {
                @if(isset($product->sale_ends_at))
                const endTime = new Date("{{ $product->sale_ends_at->toIso8601String() }}").getTime();
                
                function updateCountdown() {
                    const now = new Date().getTime();
                    const distance = endTime - now;
                    
                    if (distance < 0) {
                        countdownElement.innerHTML = "Offer expired";
                        return;
                    }
                    
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    let countdownText = '';
                    
                    if (days > 0) {
                        countdownText += `${days}d `;
                    }
                    
                    countdownText += `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    countdownElement.innerHTML = countdownText;
                }
                
                // Update immediately and then every second
                updateCountdown();
                setInterval(updateCountdown, 1000);
                @endif
            }
        }
        
        initializeCountdown();

        // Sticky Add to Cart functionality
        const stickyAddToCart = document.getElementById('stickyAddToCart');
        const productForm = document.getElementById('addToCartForm');
        const mobileQuantityInput = document.getElementById('mobileQuantity');
        const mobileDecreaseBtn = document.getElementById('mobileDecreaseQuantity');
        const mobileIncreaseBtn = document.getElementById('mobileIncreaseQuantity');
        const mobileAddToCartBtn = document.getElementById('mobileAddToCart');
        const quantityInput = document.getElementById('quantity');
        const cartNotification = document.getElementById('cartNotification');
        const closeNotificationBtn = document.getElementById('closeNotification');
        
        // Show sticky bar on scroll
        if (stickyAddToCart) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    stickyAddToCart.classList.remove('translate-y-full');
                } else {
                    stickyAddToCart.classList.add('translate-y-full');
                }
            });
        }
        
        // Sync quantity between main form and sticky bar
        if (mobileQuantityInput && quantityInput) {
            quantityInput.addEventListener('change', function() {
                mobileQuantityInput.value = this.value;
            });
            
            if (mobileDecreaseBtn) {
                mobileDecreaseBtn.addEventListener('click', function() {
                    const currentValue = parseInt(mobileQuantityInput.value);
                    if (currentValue > 1) {
                        mobileQuantityInput.value = currentValue - 1;
                        quantityInput.value = mobileQuantityInput.value;
                    }
                });
            }
            
            if (mobileIncreaseBtn) {
                mobileIncreaseBtn.addEventListener('click', function() {
                    const currentValue = parseInt(mobileQuantityInput.value);
                    const maxValue = parseInt(mobileQuantityInput.getAttribute('max'));
                    if (currentValue < maxValue) {
                        mobileQuantityInput.value = currentValue + 1;
                        quantityInput.value = mobileQuantityInput.value;
                    }
                });
            }
        }
        
        // Mobile add to cart functionality
        if (mobileAddToCartBtn && productForm) {
            mobileAddToCartBtn.addEventListener('click', function() {
                // Submit the main form
                productForm.submit();
            });
        }
        
        // Cart notification functionality
        if (productForm && cartNotification) {
            productForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(this);
                
                // Send fetch request to add to cart
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    // Show notification
                    cartNotification.classList.remove('translate-x-full');
                    
                    // Hide notification after 5 seconds
                    setTimeout(() => {
                        cartNotification.classList.add('translate-x-full');
                    }, 5000);
                })
                .catch(error => {
                    console.error('Error adding to cart:', error);
                });
            });
        }
        
        // Close notification button
        if (closeNotificationBtn && cartNotification) {
            closeNotificationBtn.addEventListener('click', function() {
                cartNotification.classList.add('translate-x-full');
            });
        }
    });
</script>
@endpush

@push('head')
<style>
    /* For image zoom effect */
    .aspect-w-1.aspect-h-1:first-child img {
        transition: transform 0.3s ease;
    }
    
    .aspect-w-1.aspect-h-1:first-child:hover img {
        transform: scale(1.05);
    }
    
    /* Thumbnail hover effect */
    .grid-cols-4 .aspect-w-1.aspect-h-1 {
        cursor: pointer;
        transition: opacity 0.2s ease;
    }
    
    .grid-cols-4 .aspect-w-1.aspect-h-1:hover {
        opacity: 0.8;
    }

    /* Image Gallery Zoom */
    .image-zoom-container {
        position: relative;
        overflow: hidden;
    }
    
    .image-zoom-container img {
        transition: transform 0.5s ease;
    }
    
    .image-zoom-container:hover img {
        transform: scale(1.25);
    }
    
    .image-zoom-lens {
        position: absolute;
        border: 1px solid #d4d4d4;
        width: 80px;
        height: 80px;
        background-repeat: no-repeat;
        cursor: none;
        display: none;
    }
</style>
@endpush
@endsection 