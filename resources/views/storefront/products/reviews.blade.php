@extends('layouts.storefront')

@section('title', $product->name . ' - Reviews')

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('storefront.products.show', $product->slug) }}" class="text-primary hover:text-primary-dark">
                    <i class="fas fa-arrow-left mr-2"></i> Back to {{ $product->name }}
                </a>
            </div>
            
            <h1 class="text-3xl font-extrabold tracking-tight text-primary">Reviews for {{ $product->name }}</h1>
            
            <div class="mt-4 flex items-center">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $product->average_rating)
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
                <p class="ml-2 text-sm text-secondary">{{ number_format($product->average_rating, 1) }} out of 5 ({{ $reviews->total() }} reviews)</p>
            </div>
        </div>
        
        <div class="space-y-8">
            @forelse($reviews as $review)
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
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
                            <span class="ml-3 text-sm font-medium text-primary">{{ $review->customer->name ?? 'Anonymous' }}</span>
                        </div>
                        <span class="text-sm text-secondary">{{ $review->created_at->format('M d, Y') }}</span>
                    </div>
                    <p class="mt-4 text-base text-secondary">{{ $review->review }}</p>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 text-center">
                    <p class="text-muted">No reviews yet for this product. Be the first to review!</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
        
        <div class="mt-8">
            <button id="writeReviewButton" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-button hover:bg-button-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button">
                Write a Review
            </button>
            
            <div id="reviewForm" class="mt-4 hidden">
                <form action="{{ route('storefront.products.review', $product) }}" method="POST" class="space-y-4 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
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
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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