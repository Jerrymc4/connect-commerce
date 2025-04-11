@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h1>
        <p class="text-gray-600 mt-1">{{ isset($product) ? 'Update product information' : 'Create a new product in your store' }}</p>
    </div>
    
    <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif
        
        <!-- Basic Information -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                    <input type="text" name="name" id="name" value="{{ $product->name ?? old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" name="sku" id="sku" value="{{ $product->sku ?? old('sku') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    @error('sku')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select name="category_id" id="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" required>
                        <option value="">Select a category</option>
                        <option value="1" {{ (isset($product) && $product->category_id == 1) || old('category_id') == 1 ? 'selected' : '' }}>Electronics</option>
                        <option value="2" {{ (isset($product) && $product->category_id == 2) || old('category_id') == 2 ? 'selected' : '' }}>Clothing</option>
                        <option value="3" {{ (isset($product) && $product->category_id == 3) || old('category_id') == 3 ? 'selected' : '' }}>Home & Kitchen</option>
                        <option value="4" {{ (isset($product) && $product->category_id == 4) || old('category_id') == 4 ? 'selected' : '' }}>Books</option>
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" required>
                        <option value="active" {{ (isset($product) && $product->status == 'active') || old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="draft" {{ (isset($product) && $product->status == 'draft') || old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="out_of_stock" {{ (isset($product) && $product->status == 'out_of_stock') || old('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                <textarea name="description" id="description" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" required>{{ $product->description ?? old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Pricing & Inventory -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Pricing & Inventory</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Regular Price ($) *</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500">$</span>
                        </div>
                        <input type="number" step="0.01" min="0" name="price" id="price" value="{{ $product->price ?? old('price') }}" class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" required>
                    </div>
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1">Sale Price ($)</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500">$</span>
                        </div>
                        <input type="number" step="0.01" min="0" name="sale_price" id="sale_price" value="{{ $product->sale_price ?? old('sale_price') }}" class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    </div>
                    @error('sale_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                    <input type="number" min="0" name="stock" id="stock" value="{{ $product->stock ?? old('stock') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" required>
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight (lbs)</label>
                    <input type="number" step="0.01" min="0" name="weight" id="weight" value="{{ $product->weight ?? old('weight') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    @error('weight')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-1">Dimensions (L × W × H inches)</label>
                    <input type="text" name="dimensions" id="dimensions" value="{{ $product->dimensions ?? old('dimensions') }}" placeholder="e.g. 10 × 5 × 2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    @error('dimensions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="track_inventory" id="track_inventory" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ (isset($product) && $product->track_inventory) || old('track_inventory') ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="track_inventory" class="font-medium text-gray-700">Track inventory</label>
                        <p class="text-gray-500">When enabled, stock will be reduced with each order.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Images -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Product Images</h2>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Main Image</label>
                <div class="mt-1 flex items-center">
                    <div class="h-32 w-32 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 bg-gray-100 flex items-center justify-center">
                        @if(isset($product) && $product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        @else
                            <i class="fas fa-image text-gray-400 text-3xl"></i>
                        @endif
                    </div>
                    <div class="ml-4">
                        <div class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm">
                            <label for="image" class="text-sm font-medium text-gray-700 cursor-pointer">
                                <span>Upload image</span>
                                <input type="file" name="image" id="image" accept="image/*" class="sr-only">
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                    </div>
                </div>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>
                <div class="mt-1 border-2 border-gray-300 border-dashed rounded-md px-6 pt-5 pb-6 flex justify-center">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="additional_images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Upload files</span>
                                <input type="file" name="additional_images[]" id="additional_images" accept="image/*" multiple class="sr-only">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB each</p>
                    </div>
                </div>
                @error('additional_images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('additional_images.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.products') }}" class="px-5 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ isset($product) ? 'Update Product' : 'Create Product' }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Preview uploaded images
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const container = event.target.closest('.flex-shrink-0');
                while (container.firstChild) {
                    container.removeChild(container.firstChild);
                }
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'h-full w-full object-cover';
                container.appendChild(img);
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush 