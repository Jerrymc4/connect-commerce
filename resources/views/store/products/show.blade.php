@extends('layouts.store')

@section('content')
<div class="container px-3 mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">Product Details</h1>
            <p class="text-secondary mt-1">View detailed information about this product</p>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('store.products', [], false) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Products
            </a>
            <a href="{{ route('store.products.edit', $product->id, false) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Edit Product
            </a>
        </div>
    </div>
    
    <!-- Flash Messages -->
    @if(session('success'))
    <div id="success-alert" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <div class="flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-4 text-green-700 hover:text-green-900 focus:outline-none" onclick="closeAlert('success-alert')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    <!-- Product Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Product Image -->
        <div class="card-enhanced">
            <div class="card-body flex items-center justify-center p-6">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="max-h-96 max-w-full object-contain rounded-lg">
                @else
                    <div class="w-full h-64 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-5xl"></i>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="card-enhanced lg:col-span-2">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-primary flex items-center">
                    <i class="fas fa-box text-blue-500 mr-2"></i>
                    Product Information
                </h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-primary mb-4">Basic Information</h4>
                        
                        <div class="mb-4">
                            <p class="text-sm text-secondary mb-1">Product Name</p>
                            <p class="font-medium text-primary">{{ $product->name }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-secondary mb-1">SKU</p>
                            <p class="font-medium text-primary">{{ $product->sku }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-secondary mb-1">Status</p>
                            <div>
                                @if($product->status === 'active')
                                    <span class="badge badge-success">Active</span>
                                @elseif($product->status === 'draft')
                                    <span class="badge badge-primary">Draft</span>
                                @else
                                    <span class="badge badge-danger">Out of Stock</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-secondary mb-1">Slug</p>
                            <p class="font-medium text-primary">{{ $product->slug ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-primary mb-4">Pricing & Inventory</h4>
                        
                        <div class="mb-4">
                            <p class="text-sm text-secondary mb-1">Price</p>
                            <p class="font-medium text-primary">${{ number_format($product->price, 2) }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-secondary mb-1">Sale Price</p>
                            <p class="font-medium text-primary">
                                @if($product->sale_price)
                                    ${{ number_format($product->sale_price, 2) }}
                                @else
                                    <span class="text-secondary italic">Not set</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-secondary mb-1">Stock</p>
                            <p class="font-medium text-primary">
                                @if($product->track_inventory)
                                    {{ $product->stock }} units
                                @else
                                    <span class="text-secondary italic">Not tracked</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-secondary mb-1">Inventory Tracking</p>
                            <p class="font-medium text-primary">
                                @if($product->track_inventory)
                                    <span class="badge badge-success">Enabled</span>
                                @else
                                    <span class="badge badge-secondary">Disabled</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <hr class="my-6 border-border-color">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-primary mb-4">Shipping Information</h4>
                        
                        <div class="mb-4">
                            <p class="text-sm text-secondary mb-1">Weight</p>
                            <p class="font-medium text-primary">
                                @if($product->weight)
                                    {{ $product->weight }} lbs
                                @else
                                    <span class="text-secondary italic">Not set</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-secondary mb-1">Dimensions</p>
                            <p class="font-medium text-primary">
                                @if($product->dimensions)
                                    {{ $product->dimensions }}
                                @else
                                    <span class="text-secondary italic">Not set</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-primary mb-4">Other Information</h4>
                        
                        <div class="mb-4">
                            <p class="text-sm text-secondary mb-1">Created At</p>
                            <p class="font-medium text-primary">{{ $product->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-secondary mb-1">Last Updated</p>
                            <p class="font-medium text-primary">{{ $product->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Description -->
    <div class="card-enhanced mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-primary flex items-center">
                <i class="fas fa-align-left text-blue-500 mr-2"></i>
                Product Description
            </h3>
        </div>
        <div class="card-body prose dark:prose-invert max-w-none">
            @if($product->description)
                <p>{{ $product->description }}</p>
            @else
                <p class="text-secondary italic">No description provided</p>
            @endif
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex justify-between mt-8">
        <div>
            <a href="{{ route('store.products', [], false) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Products
            </a>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('store.products.edit', $product->id, false) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Edit Product
            </a>
            <form action="{{ route('store.products.destroy', $product->id, false) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Product
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Close alert function
    function closeAlert(id) {
        document.getElementById(id).style.display = 'none';
    }
    
    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        // Success alerts
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(function() {
                successAlert.style.opacity = '0';
                successAlert.style.transition = 'opacity 1s';
                setTimeout(function() {
                    successAlert.style.display = 'none';
                }, 1000);
            }, 5000);
        }
    });
</script>
@endpush 