@extends('layouts.storefront')

@section('title', 'Products')

@section('content')
<div class="bg-gray-50 py-8 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                <li class="inline-flex items-center">
                    <a href="{{ route('storefront.home') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                        Home
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500 md:ml-2 font-medium">Products</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4 md:mb-0">
                {{ request('category') && $categories->firstWhere('id', request('category')) 
                   ? $categories->firstWhere('id', request('category'))->name . ' Products' 
                   : 'All Products' 
                }}
            </h1>
            
            <!-- Sort and filter controls -->
            <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
                <!-- Mobile filters toggle -->
                <button id="filter-toggle" class="md:hidden px-4 py-2 border border-gray-300 rounded-lg text-gray-700 flex items-center justify-center shadow-sm hover:bg-gray-50 transition-colors" style="background-color: white;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters
                </button>
                
                <!-- Sort dropdown -->
                <div class="relative inline-block text-left w-full sm:w-auto">
                    <select 
                        id="sort-select" 
                        onchange="window.location.href=this.value"
                        class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-4 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700"
                    >
                        <option value="{{ route('storefront.products.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}"
                            {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>
                            Latest Products
                        </option>
                        <option value="{{ route('storefront.products.index', array_merge(request()->except('sort'), ['sort' => 'price_asc'])) }}"
                            {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                            Price: Low to High
                        </option>
                        <option value="{{ route('storefront.products.index', array_merge(request()->except('sort'), ['sort' => 'price_desc'])) }}"
                            {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                            Price: High to Low
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Main content area with filters and products -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <!-- Sidebar Filters - mobile (hidden by default) -->
            <div id="filters-sidebar-mobile" class="col-span-1 md:hidden hidden mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-4">Categories</h2>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <a href="{{ route('storefront.products.index') }}" 
                               class="{{ !request('category') ? 'font-medium' : 'text-gray-700 hover:text-primary' }} transition-colors" style="color: {{ !request('category') ? 'var(--primary-color)' : '' }}; --text-primary: var(--primary-color);">
                                All Categories
                            </a>
                        </div>
                        
                        @foreach($categories as $category)
                            <div class="flex items-center">
                                <a href="{{ route('storefront.products.index', ['category' => $category->id]) }}" 
                                   class="{{ request('category') == $category->id ? 'font-medium' : 'text-gray-700 hover:text-primary' }} transition-colors" style="color: {{ request('category') == $category->id ? 'var(--primary-color)' : '' }}; --text-primary: var(--primary-color);">
                                    {{ $category->name }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Price Range Filter - Mobile -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-4">Price Range</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="price-min-mobile" class="block text-sm text-gray-500 mb-1">Min</label>
                                <input type="number" id="price-min-mobile" name="price_min" min="0" placeholder="Min" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="price-max-mobile" class="block text-sm text-gray-500 mb-1">Max</label>
                                <input type="number" id="price-max-mobile" name="price_max" min="0" placeholder="Max" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>
                        <!-- Apply price filter button (Mobile) -->
                        <button type="button" id="apply-price-mobile"
                            class="mt-2 w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            style="background-color: var(--primary-color)">
                            Apply Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Filters - desktop -->
            <div class="hidden md:block md:col-span-3 lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6 sticky top-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-4">Categories</h2>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <a href="{{ route('storefront.products.index') }}" 
                               class="{{ !request('category') ? 'font-medium' : 'text-gray-700 hover:text-primary' }} transition-colors" style="color: {{ !request('category') ? 'var(--primary-color)' : '' }}; --text-primary: var(--primary-color);">
                                All Categories
                            </a>
                        </div>
                        
                        @foreach($categories as $category)
                            <div class="flex items-center">
                                <a href="{{ route('storefront.products.index', ['category' => $category->id]) }}" 
                                   class="{{ request('category') == $category->id ? 'font-medium' : 'text-gray-700 hover:text-primary' }} transition-colors" style="color: {{ request('category') == $category->id ? 'var(--primary-color)' : '' }}; --text-primary: var(--primary-color);">
                                    {{ $category->name }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Price Range Filter - Desktop -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6 sticky top-56">
                    <h2 class="font-bold text-gray-800 text-lg mb-4">Price Range</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="price-min" class="block text-sm text-gray-500 mb-1">Min</label>
                                <input type="number" id="price-min" name="price_min" min="0" placeholder="Min" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="price-max" class="block text-sm text-gray-500 mb-1">Max</label>
                                <input type="number" id="price-max" name="price_max" min="0" placeholder="Max" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>
                        <!-- Apply price filter button (Desktop) -->
                        <button type="button" id="apply-price"
                            class="mt-2 w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            style="background-color: var(--primary-color)">
                            Apply Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="md:col-span-9 lg:col-span-10">
                @if(isset($error))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                        <p>{{ $error }}</p>
                    </div>
                @endif
                
                <!-- Results meta -->
                <div class="bg-white px-6 py-4 rounded-lg shadow-sm mb-6 flex justify-between items-center text-sm">
                    <div>
                        <p class="text-gray-500">
                            Showing 
                            <span class="font-medium text-gray-900">{{ $products->firstItem() ?? 0 }}</span>
                            to 
                            <span class="font-medium text-gray-900">{{ $products->lastItem() ?? 0 }}</span>
                            of 
                            <span class="font-medium text-gray-900">{{ $products->total() ?? 0 }}</span>
                            products
                        </p>
                    </div>
                    <div class="text-gray-500 font-medium">
                        {{ $products->total() }} results
                    </div>
                </div>
                
                @if($products->isEmpty())
                    <div class="bg-white rounded-lg shadow-sm p-10 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                        <p class="text-gray-500 mb-6">We couldn't find any products matching your criteria.</p>
                        <a href="{{ route('storefront.products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors" style="background-color: var(--primary-color)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Clear filters
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden group hover:shadow-md transition-shadow duration-300 border border-gray-100 h-full flex flex-col">
                                <a href="{{ route('storefront.products.show', $product->slug) }}" class="block">
                                    <div class="aspect-w-1 aspect-h-1 bg-gray-100 overflow-hidden">
                                        <img 
                                            src="{{ $product->image ? tenant_asset($product->image) : 'https://via.placeholder.com/300x300?text=' . urlencode($product->name) }}" 
                                            alt="{{ $product->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            loading="lazy"
                                        >
                                    </div>
                                </a>
                                <div class="p-6 flex-grow flex flex-col">
                                    <div class="mb-2">
                                        @if($product->categories->isNotEmpty())
                                            <div class="text-xs text-gray-500 mb-1">
                                                {{ $product->categories->first()->name }}
                                            </div>
                                        @endif
                                        <a href="{{ route('storefront.products.show', $product->slug) }}" class="block">
                                            <h3 class="text-gray-900 font-medium text-lg group-hover:text-primary transition-colors" style="--text-primary: var(--primary-color);">
                                                {{ $product->name }}
                                            </h3>
                                        </a>
                                    </div>
                                    <div class="mb-4 flex-grow">
                                        <p class="text-gray-500 text-sm line-clamp-2">
                                            {{ Str::limit($product->description, 100) }}
                                        </p>
                                    </div>
                                    <div class="flex justify-between items-center mt-auto">
                                        <div>
                                            <span class="text-gray-900 font-bold">${{ number_format($product->price, 2) }}</span>
                                            @if(isset($product->sale_price) && $product->sale_price > 0 && $product->sale_price < $product->price)
                                                <span class="text-gray-500 line-through text-sm ml-2">${{ number_format($product->sale_price, 2) }}</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('storefront.products.show', $product->slug) }}" 
                                           class="text-white px-4 py-1.5 rounded text-sm font-medium transition-colors" style="background-color: var(--primary-color)">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-10 flex justify-center">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile filter toggle
        const filterToggle = document.getElementById('filter-toggle');
        const filtersSidebarMobile = document.getElementById('filters-sidebar-mobile');
        
        if (filterToggle && filtersSidebarMobile) {
            filterToggle.addEventListener('click', function() {
                filtersSidebarMobile.classList.toggle('hidden');
            });
        }
        
        // Price filter functionality - Desktop
        const applyPriceBtn = document.getElementById('apply-price');
        const minPriceInput = document.getElementById('price-min');
        const maxPriceInput = document.getElementById('price-max');
        
        if (applyPriceBtn && minPriceInput && maxPriceInput) {
            applyPriceBtn.addEventListener('click', function() {
                applyPriceFilter(minPriceInput.value, maxPriceInput.value);
            });
        }
        
        // Price filter functionality - Mobile
        const applyPriceBtnMobile = document.getElementById('apply-price-mobile');
        const minPriceInputMobile = document.getElementById('price-min-mobile');
        const maxPriceInputMobile = document.getElementById('price-max-mobile');
        
        if (applyPriceBtnMobile && minPriceInputMobile && maxPriceInputMobile) {
            applyPriceBtnMobile.addEventListener('click', function() {
                applyPriceFilter(minPriceInputMobile.value, maxPriceInputMobile.value);
            });
        }
        
        // Common function for applying price filter
        function applyPriceFilter(minPrice, maxPrice) {
            if (minPrice || maxPrice) {
                // Get current URL
                const url = new URL(window.location.href);
                
                // Update or add price_min parameter
                if (minPrice) {
                    url.searchParams.set('price_min', minPrice);
                } else {
                    url.searchParams.delete('price_min');
                }
                
                // Update or add price_max parameter
                if (maxPrice) {
                    url.searchParams.set('price_max', maxPrice);
                } else {
                    url.searchParams.delete('price_max');
                }
                
                // Navigate to the filtered URL
                window.location.href = url.toString();
            }
        }
        
        // Pre-populate price inputs with URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const minPriceParam = urlParams.get('price_min');
        const maxPriceParam = urlParams.get('price_max');
        
        if (minPriceParam) {
            if (minPriceInput) minPriceInput.value = minPriceParam;
            if (minPriceInputMobile) minPriceInputMobile.value = minPriceParam;
        }
        
        if (maxPriceParam) {
            if (maxPriceInput) maxPriceInput.value = maxPriceParam;
            if (maxPriceInputMobile) maxPriceInputMobile.value = maxPriceParam;
        }
    });
</script>
@endpush

@push('styles')
<style>
    /* Pagination custom styling */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }
    
    .pagination li {
        display: inline-block;
    }
    
    .pagination li .page-link,
    .pagination li .page-item {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #4B5563;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        border: 1px solid #E5E7EB;
    }
    
    .pagination li:not(.active) .page-link:hover {
        background-color: #F3F4F6;
        color: #1F2937;
        border-color: #D1D5DB;
    }
    
    .pagination li.active .page-link {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    .pagination li.disabled .page-link {
        color: #9CA3AF;
        cursor: not-allowed;
        border-color: #E5E7EB;
        background-color: #F9FAFB;
    }
</style>
@endpush
@endsection 