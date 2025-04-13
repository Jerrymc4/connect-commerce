@extends('layouts.storefront')

@section('title', 'Categories')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Shop by Category</h1>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($categories as $category)
            <div class="col">
                <div class="card h-100">
                    <img src="{{ $category->image_url ?? 'https://via.placeholder.com/300x200' }}" 
                         class="card-img-top" 
                         alt="{{ $category->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text">{{ $category->description }}</p>
                        <a href="{{ route('storefront.products.index', ['category' => $category->id]) }}" class="btn btn-primary">
                            Browse Products
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection 