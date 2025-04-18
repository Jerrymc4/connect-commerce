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
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">Description</h3>
                    <div class="text-base text-secondary space-y-6">
                        {{ $product->description }}
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

                <form class="mt-6">
                    <div class="mt-10 flex sm:flex-col1">
                        <button type="submit" class="max-w-xs flex-1 bg-button text-white border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium hover:bg-button-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-button">
                            Add to cart
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
    });
</script>
@endpush
@endsection 