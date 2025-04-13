@extends('layouts.admin.dashboard')

@section('content')
<div class="container px-3 mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h1>
            <p class="text-secondary mt-1">{{ isset($product) ? 'Update product information' : 'Create a new product for your store' }}</p>
        </div>
        
        <div>
            <a href="{{ route('admin.products', [], false) }}" class="btn-secondary">
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
                <i class="fas fa-box text-gray-500 mr-2"></i>
                {{ isset($product) ? 'Edit Product' : 'Product Details' }}
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ isset($product) ? route('admin.products.update', $product->id, false) : route('admin.products.store', [], false) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($product))
                    @method('PUT')
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-tag text-blue-500 mr-2"></i>
                            Product Name <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ $product->name ?? old('name') }}" 
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2.5 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-200 @error('name') border-red-500 @enderror" 
                            required placeholder="Enter product name"
                            onkeyup="generateSlug(this.value)">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-barcode text-blue-500 mr-2"></i>
                            SKU <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="sku" id="sku" value="{{ $product->sku ?? old('sku') }}" 
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2.5 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-200 @error('sku') border-red-500 @enderror"
                            required placeholder="Product SKU">
                        @error('sku')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Slug (Display Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-link text-blue-500 mr-2"></i>
                            Slug (Auto-generated)
                        </label>
                        <div id="slug-display" class="p-2.5 bg-gray-50 dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 font-mono text-sm">
                            {{ $product->slug ?? 'Generated when you type the product name' }}
                        </div>
                        <input type="hidden" name="slug" id="slug" value="{{ $product->slug ?? old('slug') }}">
                    </div>
                    
                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-dollar-sign text-blue-500 mr-2"></i>
                            Price <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" min="0" name="price" id="price" 
                                value="{{ $product->price ?? old('price') }}" 
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2.5 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-200 @error('price') border-red-500 @enderror"
                                required placeholder="0.00">
                        </div>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Sale Price -->
                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-tags text-blue-500 mr-2"></i>
                            Sale Price
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" min="0" name="sale_price" id="sale_price" 
                                value="{{ $product->sale_price ?? old('sale_price') }}" 
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2.5 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-200 @error('sale_price') border-red-500 @enderror"
                                placeholder="0.00">
                        </div>
                        @error('sale_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-toggle-on text-blue-500 mr-2"></i>
                            Status <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select name="status" id="status" class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2.5 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-200 @error('status') border-red-500 @enderror" required>
                            <option value="active" {{ (isset($product) && $product->status == 'active') || old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="draft" {{ (isset($product) && $product->status == 'draft') || old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="out_of_stock" {{ (isset($product) && $product->status == 'out_of_stock') || old('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-folder text-blue-500 mr-2"></i>
                            Category
                        </label>
                        <select name="category_id" id="category_id" class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2.5 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-200 @error('category_id') border-red-500 @enderror">
                            <option value="">Select a Category</option>
                            @if(isset($categories))
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (isset($product) && $product->category_id == $category->id) || old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @if($category->children && $category->children->count() > 0)
                                        @foreach($category->children as $childCategory)
                                            <option value="{{ $childCategory->id }}" {{ (isset($product) && $product->category_id == $childCategory->id) || old('category_id') == $childCategory->id ? 'selected' : '' }}>
                                                &nbsp;&nbsp;└─ {{ $childCategory->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                        <i class="fas fa-align-left text-blue-500 mr-2"></i>
                        Description
                    </label>
                    <textarea name="description" id="description" rows="5" 
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2.5 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-200 @error('description') border-red-500 @enderror"
                        placeholder="Enter product description">{{ $product->description ?? old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Stock -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-cubes text-blue-500 mr-2"></i>
                            Stock Quantity
                        </label>
                        <input type="number" min="0" name="stock" id="stock" 
                            value="{{ $product->stock ?? old('stock', 0) }}" 
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2.5 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-200 @error('stock') border-red-500 @enderror"
                            placeholder="0">
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Weight -->
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-weight text-blue-500 mr-2"></i>
                            Weight (lbs)
                        </label>
                        <input type="number" step="0.01" min="0" name="weight" id="weight" 
                            value="{{ $product->weight ?? old('weight') }}" 
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2.5 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-200 @error('weight') border-red-500 @enderror"
                            placeholder="0.00">
                        @error('weight')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Dimensions -->
                    <div>
                        <label for="dimensions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-ruler-combined text-blue-500 mr-2"></i>
                            Dimensions (L × W × H inches)
                        </label>
                        <input type="text" name="dimensions" id="dimensions" 
                            value="{{ $product->dimensions ?? old('dimensions') }}" 
                            placeholder="e.g. 10 × 5 × 2" 
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2.5 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-200 @error('dimensions') border-red-500 @enderror">
                        @error('dimensions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Product Image -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                        <i class="fas fa-image text-blue-500 mr-2"></i>
                        Product Image
                    </label>
                    <div class="flex items-start gap-4">
                        <div class="h-32 w-32 flex-shrink-0 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                            @if(isset($product) && $product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                            @else
                                <i class="fas fa-image text-gray-400 text-3xl"></i>
                            @endif
                        </div>
                        <div>
                            <div class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors cursor-pointer">
                                <label for="image" class="cursor-pointer">
                                    <i class="fas fa-upload mr-2"></i>
                                    <span>Upload image</span>
                                    <input type="file" name="image" id="image" accept="image/*" class="sr-only">
                                </label>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">PNG, JPG, GIF up to 2MB</p>
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Inventory Tracking -->
                <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center">
                        <input type="checkbox" name="track_inventory" id="track_inventory" value="1" 
                            {{ (isset($product) && $product->track_inventory) || old('track_inventory') ? 'checked' : '' }} 
                            class="h-5 w-5 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500">
                        <label for="track_inventory" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Track inventory for this product
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 ml-7">When enabled, stock will be reduced with each sale</p>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex justify-end gap-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.products', [], false) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
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