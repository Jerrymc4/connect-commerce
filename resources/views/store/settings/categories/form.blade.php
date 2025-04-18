@extends('layouts.admin.dashboard')

@section('content')
<div class="container px-4 mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">{{ isset($category) ? 'Edit Category' : 'Add New Category' }}</h1>
            <p class="text-secondary mt-1">{{ isset($category) ? 'Update category information' : 'Create a new category for your products' }}</p>
        </div>
        
        <div>
            <a href="{{ route('admin.settings', ['tab' => 'categories'], false) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Categories
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
    
    <!-- Category Form -->
    <div class="card-enhanced">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-primary flex items-center">
                <i class="fas fa-folder text-gray-500 mr-2"></i>
                {{ isset($category) ? 'Edit Category' : 'Category Details' }}
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ isset($category) ? route('admin.settings.categories.update', $category->id, false) : route('admin.settings.categories.store', [], false) }}" method="POST" enctype="multipart/form-data" id="categoryForm">
                @csrf
                @if(isset($category))
                    @method('PUT')
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Category Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-tag text-accent mr-2"></i>
                            Category Name <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ $category->name ?? old('name') }}" 
                            class="w-full rounded-md border @error('name') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror px-3 py-2.5 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" 
                            required placeholder="Enter category name"
                            onkeyup="generateSlug(this.value)">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Slug (Display Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-link text-accent mr-2"></i>
                            Slug (Auto-generated)
                        </label>
                        <div id="slug-display" class="p-2.5 bg-white dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-mono text-sm">
                            {{ $category->slug ?? 'Generated when you type the category name' }}
                        </div>
                        <input type="hidden" name="slug" id="slug" value="{{ $category->slug ?? old('slug') }}">
                        @error('slug')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Parent Category -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-sitemap text-accent mr-2"></i>
                            Parent Category
                        </label>
                        <select name="parent_id" id="parent_id" class="w-full rounded-md border @error('parent_id') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror px-3 py-2.5 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                            <option value="">No Parent (Top Level)</option>
                            @foreach($parentCategories ?? [] as $parentCategory)
                                <option value="{{ $parentCategory->id }}" {{ (isset($category) && $category->parent_id == $parentCategory->id) || old('parent_id') == $parentCategory->id ? 'selected' : '' }}>
                                    {{ $parentCategory->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                        <i class="fas fa-align-left text-accent mr-2"></i>
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4" 
                        class="w-full rounded-md border @error('description') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror px-3 py-2.5 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                        placeholder="Enter category description">{{ $category->description ?? old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Category Image -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                        <i class="fas fa-image text-accent mr-2"></i>
                        Category Image
                    </label>
                    <div class="flex flex-col sm:flex-row items-start gap-4">
                        <div class="h-32 w-32 flex-shrink-0 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 flex items-center justify-center overflow-hidden relative">
                            <!-- Image preview container -->
                            <div id="preview-container" class="w-full h-full absolute inset-0 {{ isset($category) && $category->image ? 'block' : 'hidden' }}">
                                <img id="preview-image" src="{{ isset($category) && $category->image ? tenant_asset($category->image) : '' }}" alt="Category preview" class="h-full w-full object-cover">
                            </div>
                            
                            <!-- Placeholder icon -->
                            <div id="placeholder-container" class="w-full h-full absolute inset-0 flex items-center justify-center {{ isset($category) && $category->image ? 'hidden' : 'block' }}">
                                <i class="fas fa-image text-gray-400 text-3xl"></i>
                            </div>
                        </div>
                        
                        <div>
                            <label for="image" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-accent transition-colors cursor-pointer">
                                <i class="fas fa-upload text-accent mr-2"></i>
                                <span>Upload image</span>
                                <input type="file" name="image" id="image" accept="image/*" class="sr-only" onchange="showImagePreview(this)">
                            </label>
                            
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">PNG, JPG, GIF up to 2MB</p>
                            
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            
                            @if(isset($category) && $category->image)
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="remove_image" id="remove_image" value="1" class="text-accent focus:ring-accent h-4 w-4 rounded" onchange="toggleImagePreview(this)">
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remove current image</span>
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex justify-end gap-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.settings', ['tab' => 'categories'], false) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition-colors">
                        <i class="fas {{ isset($category) ? 'fa-save' : 'fa-plus' }} mr-2"></i>
                        {{ isset($category) ? 'Update Category' : 'Create Category' }}
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
    
    // Generate slug from category name
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
    
    // Show image preview when file is selected
    function showImagePreview(input) {
        const previewContainer = document.getElementById('preview-container');
        const placeholderContainer = document.getElementById('placeholder-container');
        const previewImage = document.getElementById('preview-image');
        
        if (input.files && input.files[0]) {
            // Create a FileReader to read the selected file
            const reader = new FileReader();
            
            // Set up the FileReader onload event
            reader.onload = function(event) {
                // Set the preview image source to the result
                previewImage.src = event.target.result;
                
                // Show the preview container and hide the placeholder
                previewContainer.classList.remove('hidden');
                previewContainer.classList.add('block');
                placeholderContainer.classList.add('hidden');
                placeholderContainer.classList.remove('block');
                
                // If there's a remove checkbox and it's checked, uncheck it
                const removeCheckbox = document.getElementById('remove_image');
                if (removeCheckbox && removeCheckbox.checked) {
                    removeCheckbox.checked = false;
                }
                
                console.log('Image preview loaded successfully');
            };
            
            // Read the file as a data URL
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Toggle image preview when remove checkbox is changed
    function toggleImagePreview(checkbox) {
        const previewContainer = document.getElementById('preview-container');
        const placeholderContainer = document.getElementById('placeholder-container');
        
        if (checkbox.checked) {
            // If checkbox is checked, hide the preview and show the placeholder
            previewContainer.classList.add('hidden');
            previewContainer.classList.remove('block');
            placeholderContainer.classList.remove('hidden');
            placeholderContainer.classList.add('block');
        } else {
            // If checkbox is unchecked, show the preview and hide the placeholder
            previewContainer.classList.remove('hidden');
            previewContainer.classList.add('block');
            placeholderContainer.classList.add('hidden');
            placeholderContainer.classList.remove('block');
        }
    }
    
    // Initialize slug if category name exists
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        if (nameInput && nameInput.value) {
            generateSlug(nameInput.value);
        }
    });
</script>
@endpush 