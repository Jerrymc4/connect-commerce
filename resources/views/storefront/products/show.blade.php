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
    });
</script>
@endpush
@endsection 