@extends('layouts.store')

@section('content')
<div class="container px-3 mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h1>
            <p class="text-secondary mt-1">{{ isset($product) ? 'Update product information' : 'Create a new product for your store' }}</p>
        </div>
        
        <div>
            <a href="{{ route('store.products', [], false) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Products
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
    
    @if(session('error'))
    <div id="error-alert" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <div class="flex justify-between items-center">
            <span>{{ session('error') }}</span>
            <button type="button" class="ml-4 text-red-700 hover:text-red-900 focus:outline-none" onclick="closeAlert('error-alert')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    <!-- Product Form -->
    <div class="card-enhanced">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-primary flex items-center">
                <i class="fas fa-box text-blue-500 mr-2"></i>
                {{ isset($product) ? 'Edit Product' : 'Product Details' }}
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ isset($product) ? route('store.products.update', $product->id, false) : route('store.products.store', [], false) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($product))
                    @method('PUT')
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Product Name -->
                    <div>
                        <label for="name" class="form-label">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ $product->name ?? old('name') }}" 
                            class="form-control @error('name') border-red-500 @enderror" required
                            onkeyup="generateSlug(this.value)">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- SKU -->
                    <div>
                        <label for="sku" class="form-label">SKU <span class="text-red-500">*</span></label>
                        <input type="text" name="sku" id="sku" value="{{ $product->sku ?? old('sku') }}" 
                            class="form-control @error('sku') border-red-500 @enderror" required>
                        @error('sku')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Slug (Display Only) -->
                    <div>
                        <label class="form-label">Slug (Auto-generated)</label>
                        <div id="slug-display" class="p-2 bg-gray-100 dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400">
                            {{ $product->slug ?? 'Generated when you type the product name' }}
                        </div>
                        <input type="hidden" name="slug" id="slug" value="{{ $product->slug ?? old('slug') }}">
                    </div>
                    
                    <!-- Price -->
                    <div>
                        <label for="price" class="form-label">Price <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">$</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="price" id="price" 
                                value="{{ $product->price ?? old('price') }}" 
                                class="form-control pl-7 @error('price') border-red-500 @enderror" required>
                        </div>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Sale Price -->
                    <div>
                        <label for="sale_price" class="form-label">Sale Price</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">$</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="sale_price" id="sale_price" 
                                value="{{ $product->sale_price ?? old('sale_price') }}" 
                                class="form-control pl-7 @error('sale_price') border-red-500 @enderror">
                        </div>
                        @error('sale_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') border-red-500 @enderror" required>
                            <option value="active" {{ (isset($product) && $product->status == 'active') || old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="draft" {{ (isset($product) && $product->status == 'draft') || old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="out_of_stock" {{ (isset($product) && $product->status == 'out_of_stock') || old('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="5" 
                        class="form-control @error('description') border-red-500 @enderror">{{ $product->description ?? old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Stock -->
                    <div>
                        <label for="stock" class="form-label">Stock Quantity</label>
                        <input type="number" min="0" name="stock" id="stock" 
                            value="{{ $product->stock ?? old('stock', 0) }}" 
                            class="form-control @error('stock') border-red-500 @enderror">
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Weight -->
                    <div>
                        <label for="weight" class="form-label">Weight (lbs)</label>
                        <input type="number" step="0.01" min="0" name="weight" id="weight" 
                            value="{{ $product->weight ?? old('weight') }}" 
                            class="form-control @error('weight') border-red-500 @enderror">
                        @error('weight')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Dimensions -->
                    <div>
                        <label for="dimensions" class="form-label">Dimensions (L × W × H inches)</label>
                        <input type="text" name="dimensions" id="dimensions" 
                            value="{{ $product->dimensions ?? old('dimensions') }}" 
                            placeholder="e.g. 10 × 5 × 2" 
                            class="form-control @error('dimensions') border-red-500 @enderror">
                        @error('dimensions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Product Image -->
                <div class="mb-6">
                    <label class="form-label">Product Image</label>
                    <div class="flex items-start gap-4">
                        <div class="h-32 w-32 flex-shrink-0 rounded border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                            @if(isset($product) && $product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                            @else
                                <i class="fas fa-image text-gray-400 text-3xl"></i>
                            @endif
                        </div>
                        <div>
                            <div class="relative bg-white dark:bg-gray-800 py-2 px-3 border border-gray-300 dark:border-gray-600 rounded shadow-sm cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 mb-2">
                                <label for="image" class="cursor-pointer text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span>Upload image</span>
                                    <input type="file" name="image" id="image" accept="image/*" class="sr-only">
                                </label>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB</p>
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Inventory Tracking -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="track_inventory" id="track_inventory" value="1" 
                            {{ (isset($product) && $product->track_inventory) || old('track_inventory') ? 'checked' : '' }} 
                            class="h-4 w-4 text-blue-600 dark:text-blue-500 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500">
                        <label for="track_inventory" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Track inventory for this product
                        </label>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex justify-end gap-3">
                    <a href="{{ route('store.products', [], false) }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">
                        <i class="fas {{ isset($product) ? 'fa-save' : 'fa-plus' }} mr-2"></i>
                        {{ isset($product) ? 'Update Product' : 'Create Product' }}
                    </button>
                </div>
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
        
        // Error alerts
        const errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            setTimeout(function() {
                errorAlert.style.opacity = '0';
                errorAlert.style.transition = 'opacity 1s';
                setTimeout(function() {
                    errorAlert.style.display = 'none';
                }, 1000);
            }, 5000);
        }
    });
    
    // Generate slug from product name
    function generateSlug(name) {
        // Convert to lowercase, replace spaces with hyphens, remove special characters
        let slug = name.toLowerCase()
                      .replace(/\s+/g, '-')
                      .replace(/[^\w\-]+/g, '')
                      .replace(/\-\-+/g, '-')
                      .replace(/^-+|-+$/g, '');
        
        document.getElementById('slug-display').innerText = slug;
        document.getElementById('slug').value = slug;
    }
    
    // Initialize slug if product name exists
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        if (nameInput && nameInput.value) {
            generateSlug(nameInput.value);
        }
    });
</script>
@endpush 