@extends('layouts.storefront')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('storefront.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('storefront.products.index') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6 mb-4">
            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/600x600' }}" 
                 class="img-fluid rounded" 
                 alt="{{ $product->name }}">
        </div>
        
        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="mb-3">{{ $product->name }}</h1>
            
            <div class="mb-3">
                <span class="h3 text-primary">${{ number_format($product->price, 2) }}</span>
                @if(isset($product->compare_price) && $product->compare_price > $product->price)
                    <span class="text-muted text-decoration-line-through ms-2">
                        ${{ number_format($product->compare_price, 2) }}
                    </span>
                    <span class="badge bg-danger ms-2">
                        {{ round((1 - $product->price / $product->compare_price) * 100) }}% OFF
                    </span>
                @endif
            </div>
            
            <div class="mb-4">
                <p>{{ $product->description }}</p>
            </div>
            
            @if(isset($product->stock) && $product->stock > 0)
                <div class="mb-3">
                    <span class="badge bg-success">In Stock ({{ $product->stock }})</span>
                </div>
            @else
                <div class="mb-3">
                    <span class="badge bg-secondary">Out of Stock</span>
                </div>
            @endif
            
            <div class="mb-4">
                <div class="d-flex align-items-center">
                    <div class="input-group me-3" style="width: 130px;">
                        <button class="btn btn-outline-secondary" type="button" id="decrease-qty">-</button>
                        <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="{{ $product->stock ?? 10 }}">
                        <button class="btn btn-outline-secondary" type="button" id="increase-qty">+</button>
                    </div>
                    
                    <button class="btn btn-primary" id="add-to-cart">
                        <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                    </button>
                </div>
            </div>
            
            @if(isset($product->sku) || isset($product->categories))
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Product Details</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @if(isset($product->sku))
                        <li class="mb-2">
                            <strong>SKU:</strong> {{ $product->sku }}
                        </li>
                        @endif
                        
                        @if(isset($product->categories) && $product->categories->count() > 0)
                        <li class="mb-2">
                            <strong>Categories:</strong> 
                            @foreach($product->categories as $category)
                                <a href="{{ route('storefront.products.index', ['category' => $category->id]) }}">
                                    {{ $category->name }}
                                </a>
                                @if(!$loop->last), @endif
                            @endforeach
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($relatedProducts as $relatedProduct)
                <div class="col">
                    <div class="card h-100 product-card">
                        <img src="{{ $relatedProduct->image_url ?? 'https://via.placeholder.com/300x300' }}" 
                             class="card-img-top" 
                             alt="{{ $relatedProduct->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                            <p class="text-muted mb-2">{{ Str::limit($relatedProduct->description, 60) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0">${{ number_format($relatedProduct->price, 2) }}</span>
                                <a href="{{ route('storefront.products.show', $relatedProduct->slug) }}" class="btn btn-sm btn-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity input handlers
        const qtyInput = document.getElementById('quantity');
        const decreaseBtn = document.getElementById('decrease-qty');
        const increaseBtn = document.getElementById('increase-qty');
        
        decreaseBtn.addEventListener('click', function() {
            const currentValue = parseInt(qtyInput.value);
            if (currentValue > 1) {
                qtyInput.value = currentValue - 1;
            }
        });
        
        increaseBtn.addEventListener('click', function() {
            const currentValue = parseInt(qtyInput.value);
            const maxValue = parseInt(qtyInput.getAttribute('max'));
            if (currentValue < maxValue) {
                qtyInput.value = currentValue + 1;
            }
        });
        
        // Add to cart button (placeholder functionality)
        const addToCartBtn = document.getElementById('add-to-cart');
        addToCartBtn.addEventListener('click', function() {
            const quantity = parseInt(qtyInput.value);
            alert(`Added ${quantity} of "${document.querySelector('h1').textContent}" to cart!`);
            // In a real implementation, this would call an API or submit a form
        });
    });
</script>
@endpush
@endsection 