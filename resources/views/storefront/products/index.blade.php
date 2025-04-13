@extends('layouts.storefront')

@section('title', 'Products')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">All Products</h1>
    
    @if(isset($error))
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endif
    
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Categories</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('storefront.products.index') }}" class="{{ !request()->has('category') ? 'fw-bold' : '' }}">
                                All Categories
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li class="mb-2">
                                <a href="{{ route('storefront.products.index', ['category' => $category->id]) }}" 
                                   class="{{ request('category') == $category->id ? 'fw-bold' : '' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Sort By</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('storefront.products.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}" 
                               class="{{ request('sort') == 'newest' || !request('sort') ? 'fw-bold' : '' }}">
                                Newest
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('storefront.products.index', array_merge(request()->except('sort'), ['sort' => 'price_asc'])) }}" 
                               class="{{ request('sort') == 'price_asc' ? 'fw-bold' : '' }}">
                                Price (Low to High)
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('storefront.products.index', array_merge(request()->except('sort'), ['sort' => 'price_desc'])) }}" 
                               class="{{ request('sort') == 'price_desc' ? 'fw-bold' : '' }}">
                                Price (High to Low)
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="row mb-4">
                <div class="col-12">
                    <p class="text-muted">
                        Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() ?? 0 }} products
                    </p>
                </div>
            </div>
            
            @if($products->isEmpty())
                <div class="alert alert-info">
                    No products found. Please try a different category or search term.
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($products as $product)
                        <div class="col">
                            <div class="card h-100 product-card">
                                <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x300' }}" 
                                     class="card-img-top" 
                                     alt="{{ $product->name }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="text-muted mb-2">{{ Str::limit($product->description, 60) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 mb-0">${{ number_format($product->price, 2) }}</span>
                                        <a href="{{ route('storefront.products.show', $product->slug) }}" class="btn btn-sm btn-primary">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 