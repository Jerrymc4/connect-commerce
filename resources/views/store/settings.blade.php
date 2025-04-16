@extends('layouts.admin.dashboard')

@section('content')
<div class="container px-4 mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-primary">Store Settings</h1>
        <p class="text-secondary mt-1">Manage all aspects of your store</p>
    </div>
    
    <!-- Notifications -->
    @if(session('success'))
    <div class="bg-green-100 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif
    
    @if(session('error'))
    <div class="bg-red-100 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Tabs -->
    <div class="mb-6 border-b border-border-color">
        <div class="flex flex-wrap -mb-px">
            <a href="{{ route('admin.settings', ['tab' => 'general'], false) }}" class="inline-block py-4 px-4 text-center border-b-2 {{ $activeTab == 'general' ? 'border-accent text-accent font-medium' : 'border-transparent hover:text-primary hover:border-border-color' }}">
                <i class="fas fa-cog mr-2"></i> General
            </a>
            <a href="{{ route('admin.settings', ['tab' => 'theme'], false) }}" class="inline-block py-4 px-4 text-center border-b-2 {{ $activeTab == 'theme' ? 'border-accent text-accent font-medium' : 'border-transparent hover:text-primary hover:border-border-color' }}">
                <i class="fas fa-paint-brush mr-2"></i> Theme
            </a>
            <a href="{{ route('admin.settings', ['tab' => 'categories'], false) }}" class="inline-block py-4 px-4 text-center border-b-2 {{ $activeTab == 'categories' ? 'border-accent text-accent font-medium' : 'border-transparent hover:text-primary hover:border-border-color' }}">
                <i class="fas fa-list mr-2"></i> Categories
            </a>
            <a href="{{ route('admin.settings', ['tab' => 'discounts'], false) }}" class="inline-block py-4 px-4 text-center border-b-2 {{ $activeTab == 'discounts' ? 'border-accent text-accent font-medium' : 'border-transparent hover:text-primary hover:border-border-color' }}">
                <i class="fas fa-tag mr-2"></i> Discounts
            </a>
        </div>
    </div>
    
    <!-- General Settings Tab -->
    @if($activeTab == 'general')
    <div class="bg-card rounded-lg shadow-sm border border-border-color p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="general_settings">
            
            <!-- General Info -->
            <div>
                <h2 class="text-xl font-medium text-primary mb-4">General Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="general[store_name]" class="block text-sm font-medium text-primary mb-1">Store Name <span class="text-red-500">*</span></label>
                        <input type="text" name="general[store_name]" id="general[store_name]" value="{{ $storeSettings['general']['store_name'] ?? old('general.store_name') }}" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent" required>
                        @error('general.store_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="general[store_email]" class="block text-sm font-medium text-primary mb-1">Store Email <span class="text-red-500">*</span></label>
                        <input type="email" name="general[store_email]" id="general[store_email]" value="{{ $storeSettings['general']['store_email'] ?? old('general.store_email') }}" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent" required>
                        @error('general.store_email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="general[store_phone]" class="block text-sm font-medium text-primary mb-1">Store Phone</label>
                        <input type="text" name="general[store_phone]" id="general[store_phone]" value="{{ $storeSettings['general']['store_phone'] ?? old('general.store_phone') }}" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                        @error('general.store_phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="general[legal_name]" class="block text-sm font-medium text-primary mb-1">Legal Business Name</label>
                        <input type="text" name="general[legal_name]" id="general[legal_name]" value="{{ $storeSettings['general']['legal_name'] ?? old('general.legal_name') }}" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                        @error('general.legal_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <label for="general[store_address]" class="block text-sm font-medium text-primary mb-1">Store Address</label>
                    <textarea name="general[store_address]" id="general[store_address]" rows="3" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">{{ $storeSettings['general']['store_address'] ?? old('general.store_address') }}</textarea>
                    @error('general.store_address')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Regional Settings -->
            <div class="pt-6 border-t border-border-color">
                <h2 class="text-xl font-medium text-primary mb-4">Regional Settings</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="regional[currency]" class="block text-sm font-medium text-primary mb-1">Currency <span class="text-red-500">*</span></label>
                        <select name="regional[currency]" id="regional[currency]" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent" required>
                            @foreach($currencies as $code => $name)
                            <option value="{{ $code }}" {{ ($storeSettings['regional']['currency'] ?? '') == $code ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('regional.currency')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="regional[weight_unit]" class="block text-sm font-medium text-primary mb-1">Weight Unit <span class="text-red-500">*</span></label>
                        <select name="regional[weight_unit]" id="regional[weight_unit]" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent" required>
                            @foreach($weightUnits as $code => $name)
                            <option value="{{ $code }}" {{ ($storeSettings['regional']['weight_unit'] ?? '') == $code ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('regional.weight_unit')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="regional[dimension_unit]" class="block text-sm font-medium text-primary mb-1">Dimension Unit <span class="text-red-500">*</span></label>
                        <select name="regional[dimension_unit]" id="regional[dimension_unit]" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent" required>
                            @foreach($dimensionUnits as $code => $name)
                            <option value="{{ $code }}" {{ ($storeSettings['regional']['dimension_unit'] ?? '') == $code ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('regional.dimension_unit')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="pt-6 border-t border-border-color flex justify-end space-x-3">
                <button type="reset" class="px-4 py-2 border border-border-color rounded-md text-sm font-medium text-primary bg-body hover:bg-card focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                    Reset
                </button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
    @endif
    
    <!-- Theme Settings Tab -->
    @if($activeTab == 'theme')
    <div class="bg-card rounded-lg shadow-sm border border-border-color p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="theme-settings-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="theme_settings">
            
            <!-- Theme Settings Tabs -->
            <div class="mb-6 border-b border-border-color">
                <div class="flex flex-wrap -mb-px">
                    <button type="button" data-tab="colors" class="inline-block py-4 px-4 text-center border-b-2 font-medium theme-tab active border-accent text-accent bg-accent/5">
                        <i class="fas fa-palette mr-2"></i> Colors
                    </button>
                    <button type="button" data-tab="typography" class="inline-block py-4 px-4 text-center border-b-2 font-medium theme-tab border-transparent text-primary hover:text-accent hover:border-border-color">
                        <i class="fas fa-font mr-2"></i> Typography
                    </button>
                    <button type="button" data-tab="products" class="inline-block py-4 px-4 text-center border-b-2 font-medium theme-tab border-transparent text-primary hover:text-accent hover:border-border-color">
                        <i class="fas fa-box mr-2"></i> Products
                    </button>
                    <button type="button" data-tab="navigation" class="inline-block py-4 px-4 text-center border-b-2 font-medium theme-tab border-transparent text-primary hover:text-accent hover:border-border-color">
                        <i class="fas fa-bars mr-2"></i> Navigation
                    </button>
                    <button type="button" data-tab="social" class="inline-block py-4 px-4 text-center border-b-2 font-medium theme-tab border-transparent text-primary hover:text-accent hover:border-border-color">
                        <i class="fas fa-share-alt mr-2"></i> Social
                    </button>
                </div>
            </div>

            <!-- Colors Tab Content -->
            <div class="theme-tab-content active" id="colors-tab">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Brand Colors -->
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-primary mb-4">Brand Colors</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="primary_color" class="block text-sm font-medium text-primary mb-1">Primary Color</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-border-color bg-body">
                                        <input type="color" name="primary_color" id="primary_color_picker" value="{{ $themeSettings['primary_color'] ?? '#3B82F6' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                    </span>
                                    <input type="text" name="primary_color" id="primary_color" value="{{ $themeSettings['primary_color'] ?? '#3B82F6' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-border-color bg-input text-primary focus:ring-accent focus:border-accent">
                                </div>
                                <p class="mt-1 text-xs text-secondary">Main brand color used throughout the store</p>
                            </div>
                            
                            <div>
                                <label for="secondary_color" class="block text-sm font-medium text-primary mb-1">Secondary Color</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-border-color bg-body">
                                        <input type="color" name="secondary_color" id="secondary_color_picker" value="{{ $themeSettings['secondary_color'] ?? '#10B981' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                    </span>
                                    <input type="text" name="secondary_color" id="secondary_color" value="{{ $themeSettings['secondary_color'] ?? '#10B981' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-border-color bg-input text-primary focus:ring-accent focus:border-accent">
                                </div>
                                <p class="mt-1 text-xs text-secondary">Accent color for secondary elements</p>
                            </div>
                        </div>
                    </div>

                    <!-- Background Colors -->
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-primary mb-4">Background Colors</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="body_bg_color" class="block text-sm font-medium text-primary mb-1">Body Background</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-border-color bg-body">
                                        <input type="color" name="body_bg_color" id="body_bg_color_picker" value="{{ $themeSettings['body_bg_color'] ?? '#F9FAFB' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                    </span>
                                    <input type="text" name="body_bg_color" id="body_bg_color" value="{{ $themeSettings['body_bg_color'] ?? '#F9FAFB' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-border-color bg-input text-primary focus:ring-accent focus:border-accent">
                                </div>
                                <p class="mt-1 text-xs text-secondary">Main background color of the store</p>
                            </div>
                            
                            <div>
                                <label for="card_bg_color" class="block text-sm font-medium text-primary mb-1">Card Background</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-border-color bg-body">
                                        <input type="color" name="card_bg_color" id="card_bg_color_picker" value="{{ $themeSettings['card_bg_color'] ?? '#FFFFFF' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                    </span>
                                    <input type="text" name="card_bg_color" id="card_bg_color" value="{{ $themeSettings['card_bg_color'] ?? '#FFFFFF' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-border-color bg-input text-primary focus:ring-accent focus:border-accent">
                                </div>
                                <p class="mt-1 text-xs text-secondary">Background color for cards and containers</p>
                            </div>
                        </div>
                    </div>

                    <!-- Text Colors -->
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-primary mb-4">Text Colors</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="text_color" class="block text-sm font-medium text-primary mb-1">Body Text</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-border-color bg-body">
                                        <input type="color" name="text_color" id="text_color_picker" value="{{ $themeSettings['text_color'] ?? '#111827' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                    </span>
                                    <input type="text" name="text_color" id="text_color" value="{{ $themeSettings['text_color'] ?? '#111827' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-border-color bg-input text-primary focus:ring-accent focus:border-accent">
                                </div>
                                <p class="mt-1 text-xs text-secondary">Color for regular text content</p>
                            </div>
                            
                            <div>
                                <label for="link_color" class="block text-sm font-medium text-primary mb-1">Default Link Color</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-border-color bg-body">
                                        <input type="color" name="link_color" id="link_color_picker" value="{{ $themeSettings['link_color'] ?? '#2563EB' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                    </span>
                                    <input type="text" name="link_color" id="link_color" value="{{ $themeSettings['link_color'] ?? '#2563EB' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-border-color bg-input text-primary focus:ring-accent focus:border-accent">
                                </div>
                                <p class="mt-1 text-xs text-secondary">Default color for links</p>
                            </div>

                            <div>
                                <label for="card_link_color" class="block text-sm font-medium text-primary mb-1">Card Link Color</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-border-color bg-body">
                                        <input type="color" name="card_link_color" id="card_link_color_picker" value="{{ $themeSettings['card_link_color'] ?? '#2563EB' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                    </span>
                                    <input type="text" name="card_link_color" id="card_link_color" value="{{ $themeSettings['card_link_color'] ?? '#2563EB' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-border-color bg-input text-primary focus:ring-accent focus:border-accent">
                                </div>
                                <p class="mt-1 text-xs text-secondary">Color for links within cards</p>
                            </div>

                            <div>
                                <label for="category_link_color" class="block text-sm font-medium text-primary mb-1">Category Link Color</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-border-color bg-body">
                                        <input type="color" name="category_link_color" id="category_link_color_picker" value="{{ $themeSettings['category_link_color'] ?? '#2563EB' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                    </span>
                                    <input type="text" name="category_link_color" id="category_link_color" value="{{ $themeSettings['category_link_color'] ?? '#2563EB' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-border-color bg-input text-primary focus:ring-accent focus:border-accent">
                                </div>
                                <p class="mt-1 text-xs text-secondary">Color for category links</p>
                            </div>

                            <div>
                                <label for="product_link_color" class="block text-sm font-medium text-primary mb-1">Product Link Color</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-border-color bg-body">
                                        <input type="color" name="product_link_color" id="product_link_color_picker" value="{{ $themeSettings['product_link_color'] ?? '#2563EB' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                    </span>
                                    <input type="text" name="product_link_color" id="product_link_color" value="{{ $themeSettings['product_link_color'] ?? '#2563EB' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-border-color bg-input text-primary focus:ring-accent focus:border-accent">
                                </div>
                                <p class="mt-1 text-xs text-secondary">Color for product links</p>
                            </div>

                            <div>
                                <label for="cart_badge_bg_color" class="block text-sm font-medium text-primary mb-1">Cart Badge Color</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-border-color bg-body">
                                        <input type="color" name="cart_badge_bg_color" id="cart_badge_bg_color_picker" value="{{ $themeSettings['cart_badge_bg_color'] ?? '#EF4444' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                    </span>
                                    <input type="text" name="cart_badge_bg_color" id="cart_badge_bg_color" value="{{ $themeSettings['cart_badge_bg_color'] ?? '#EF4444' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-border-color bg-input text-primary focus:ring-accent focus:border-accent">
                                </div>
                                <p class="mt-1 text-xs text-secondary">Background color for cart badge</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Typography Tab Content -->
            <div class="theme-tab-content hidden" id="typography-tab">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Font Selection -->
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-primary mb-4">Font Selection</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="font_family" class="block text-sm font-medium text-primary mb-1">Font Family</label>
                                <select name="font_family" id="font_family" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                                    @foreach($availableFonts as $value => $label)
                                        <option value="{{ $value }}" {{ ($themeSettings['font_family'] ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-secondary">Main font used throughout the store</p>
                            </div>
                        </div>
                    </div>

                    <!-- Heading Sizes -->
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-primary mb-4">Heading Sizes</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="heading_size" class="block text-sm font-medium text-primary mb-1">Heading Size</label>
                                <select name="heading_size" id="heading_size" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                                    <option value="small" {{ ($themeSettings['heading_size'] ?? '') == 'small' ? 'selected' : '' }}>Small</option>
                                    <option value="medium" {{ ($themeSettings['heading_size'] ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="large" {{ ($themeSettings['heading_size'] ?? '') == 'large' ? 'selected' : '' }}>Large</option>
                                </select>
                                <p class="mt-1 text-xs text-secondary">Size for all headings</p>
                            </div>
                        </div>
                    </div>

                    <!-- Text Sizes -->
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-primary mb-4">Text Sizes</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="text_size" class="block text-sm font-medium text-primary mb-1">Body Text Size</label>
                                <select name="text_size" id="text_size" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                                    <option value="small" {{ ($themeSettings['text_size'] ?? '') == 'small' ? 'selected' : '' }}>Small</option>
                                    <option value="medium" {{ ($themeSettings['text_size'] ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="large" {{ ($themeSettings['text_size'] ?? '') == 'large' ? 'selected' : '' }}>Large</option>
                                </select>
                                <p class="mt-1 text-xs text-secondary">Size for regular text content</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Tab Content -->
            <div class="theme-tab-content hidden" id="products-tab">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Card Style -->
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-primary mb-4">Product Card Style</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="product_card_style" class="block text-sm font-medium text-primary mb-1">Card Style</label>
                                <select name="product_card_style" id="product_card_style" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                                    <option value="minimal" {{ ($themeSettings['product_card_style'] ?? '') == 'minimal' ? 'selected' : '' }}>Minimal</option>
                                    <option value="detailed" {{ ($themeSettings['product_card_style'] ?? '') == 'detailed' ? 'selected' : '' }}>Detailed</option>
                                    <option value="compact" {{ ($themeSettings['product_card_style'] ?? '') == 'compact' ? 'selected' : '' }}>Compact</option>
                                </select>
                                <p class="mt-1 text-xs text-secondary">Style of product cards in listings</p>
                            </div>
                        </div>
                    </div>

                    <!-- Image Options -->
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-primary mb-4">Image Options</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="product_image_size" class="block text-sm font-medium text-primary mb-1">Image Size</label>
                                <select name="product_image_size" id="product_image_size" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                                    <option value="small" {{ ($themeSettings['product_image_size'] ?? '') == 'small' ? 'selected' : '' }}>Small</option>
                                    <option value="medium" {{ ($themeSettings['product_image_size'] ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="large" {{ ($themeSettings['product_image_size'] ?? '') == 'large' ? 'selected' : '' }}>Large</option>
                                </select>
                                <p class="mt-1 text-xs text-secondary">Size of product images in listings</p>
                            </div>
                            
                            <div>
                                <label for="product_image_hover" class="block text-sm font-medium text-primary mb-1">Hover Effect</label>
                                <select name="product_image_hover" id="product_image_hover" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                                    <option value="none" {{ ($themeSettings['product_image_hover'] ?? '') == 'none' ? 'selected' : '' }}>None</option>
                                    <option value="zoom" {{ ($themeSettings['product_image_hover'] ?? '') == 'zoom' ? 'selected' : '' }}>Zoom</option>
                                    <option value="slide" {{ ($themeSettings['product_image_hover'] ?? '') == 'slide' ? 'selected' : '' }}>Slide</option>
                                </select>
                                <p class="mt-1 text-xs text-secondary">Effect when hovering over product images</p>
                            </div>
                        </div>
                    </div>

                    <!-- Information Display -->
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-primary mb-4">Information Display</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="show_product_price" id="show_product_price" class="focus:ring-accent h-4 w-4 text-accent border-border-color rounded" {{ ($themeSettings['show_product_price'] ?? true) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="show_product_price" class="font-medium text-primary">Show Price</label>
                                    <p class="text-secondary">Display product prices on cards</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="show_product_rating" id="show_product_rating" class="focus:ring-accent h-4 w-4 text-accent border-border-color rounded" {{ ($themeSettings['show_product_rating'] ?? true) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="show_product_rating" class="font-medium text-primary">Show Rating</label>
                                    <p class="text-secondary">Display product ratings on cards</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="show_product_description" id="show_product_description" class="focus:ring-accent h-4 w-4 text-accent border-border-color rounded" {{ ($themeSettings['show_product_description'] ?? false) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="show_product_description" class="font-medium text-primary">Show Description</label>
                                    <p class="text-secondary">Display product descriptions on cards</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Tab Content -->
            <div class="theme-tab-content hidden" id="navigation-tab">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Menu Style -->
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-primary mb-4">Menu Style</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="menu_style" class="block text-sm font-medium text-primary mb-1">Menu Style</label>
                                <select name="menu_style" id="menu_style" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                                    <option value="dropdown" {{ ($themeSettings['menu_style'] ?? '') == 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                                    <option value="mega" {{ ($themeSettings['menu_style'] ?? '') == 'mega' ? 'selected' : '' }}>Mega Menu</option>
                                </select>
                                <p class="mt-1 text-xs text-secondary">Style of the main navigation menu</p>
                            </div>
                            
                            <div>
                                <label for="menu_item_spacing" class="block text-sm font-medium text-primary mb-1">Item Spacing</label>
                                <select name="menu_item_spacing" id="menu_item_spacing" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                                    <option value="compact" {{ ($themeSettings['menu_item_spacing'] ?? '') == 'compact' ? 'selected' : '' }}>Compact</option>
                                    <option value="comfortable" {{ ($themeSettings['menu_item_spacing'] ?? '') == 'comfortable' ? 'selected' : '' }}>Comfortable</option>
                                    <option value="spacious" {{ ($themeSettings['menu_item_spacing'] ?? '') == 'spacious' ? 'selected' : '' }}>Spacious</option>
                                </select>
                                <p class="mt-1 text-xs text-secondary">Spacing between menu items</p>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu -->
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-primary mb-4">Mobile Menu</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="mobile_menu_style" class="block text-sm font-medium text-primary mb-1">Mobile Menu Style</label>
                                <select name="mobile_menu_style" id="mobile_menu_style" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                                    <option value="slide" {{ ($themeSettings['mobile_menu_style'] ?? '') == 'slide' ? 'selected' : '' }}>Slide-in</option>
                                    <option value="full" {{ ($themeSettings['mobile_menu_style'] ?? '') == 'full' ? 'selected' : '' }}>Full-screen</option>
                                </select>
                                <p class="mt-1 text-xs text-secondary">Style of the mobile navigation menu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Tab Content -->
            <div class="theme-tab-content hidden" id="social-tab">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Icon Style -->
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-primary mb-4">Icon Style</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="social_icon_style" class="block text-sm font-medium text-primary mb-1">Icon Style</label>
                                <select name="social_icon_style" id="social_icon_style" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                                    <option value="minimal" {{ ($themeSettings['social_icon_style'] ?? '') == 'minimal' ? 'selected' : '' }}>Minimal</option>
                                    <option value="rounded" {{ ($themeSettings['social_icon_style'] ?? '') == 'rounded' ? 'selected' : '' }}>Rounded</option>
                                    <option value="square" {{ ($themeSettings['social_icon_style'] ?? '') == 'square' ? 'selected' : '' }}>Square</option>
                                </select>
                                <p class="mt-1 text-xs text-secondary">Style of social media icons</p>
                            </div>
                            
                            <div>
                                <label for="social_icon_size" class="block text-sm font-medium text-primary mb-1">Icon Size</label>
                                <select name="social_icon_size" id="social_icon_size" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                                    <option value="small" {{ ($themeSettings['social_icon_size'] ?? '') == 'small' ? 'selected' : '' }}>Small</option>
                                    <option value="medium" {{ ($themeSettings['social_icon_size'] ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="large" {{ ($themeSettings['social_icon_size'] ?? '') == 'large' ? 'selected' : '' }}>Large</option>
                                </select>
                                <p class="mt-1 text-xs text-secondary">Size of social media icons</p>
                            </div>
                        </div>
                    </div>

                    <!-- Platform Selection -->
                    <div class="col-span-2">
                        <h3 class="text-lg font-medium text-primary mb-4">Platform Selection</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="show_facebook" id="show_facebook" class="focus:ring-accent h-4 w-4 text-accent border-border-color rounded" {{ ($themeSettings['show_facebook'] ?? true) ? 'checked' : '' }}>
                                </div>
                                <div class="flex-1">
                                    <label for="show_facebook" class="block text-sm font-medium text-primary">Facebook</label>
                                    <input type="url" name="facebook_url" id="facebook_url" value="{{ $themeSettings['facebook_url'] ?? '' }}" placeholder="https://facebook.com/yourpage" class="mt-1 block w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent text-sm">
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="show_twitter" id="show_twitter" class="focus:ring-accent h-4 w-4 text-accent border-border-color rounded" {{ ($themeSettings['show_twitter'] ?? true) ? 'checked' : '' }}>
                                </div>
                                <div class="flex-1">
                                    <label for="show_twitter" class="block text-sm font-medium text-primary">Twitter</label>
                                    <input type="url" name="twitter_url" id="twitter_url" value="{{ $themeSettings['twitter_url'] ?? '' }}" placeholder="https://twitter.com/yourhandle" class="mt-1 block w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent text-sm">
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="show_instagram" id="show_instagram" class="focus:ring-accent h-4 w-4 text-accent border-border-color rounded" {{ ($themeSettings['show_instagram'] ?? true) ? 'checked' : '' }}>
                                </div>
                                <div class="flex-1">
                                    <label for="show_instagram" class="block text-sm font-medium text-primary">Instagram</label>
                                    <input type="url" name="instagram_url" id="instagram_url" value="{{ $themeSettings['instagram_url'] ?? '' }}" placeholder="https://instagram.com/yourhandle" class="mt-1 block w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent text-sm">
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="show_pinterest" id="show_pinterest" class="focus:ring-accent h-4 w-4 text-accent border-border-color rounded" {{ ($themeSettings['show_pinterest'] ?? true) ? 'checked' : '' }}>
                                </div>
                                <div class="flex-1">
                                    <label for="show_pinterest" class="block text-sm font-medium text-primary">Pinterest</label>
                                    <input type="url" name="pinterest_url" id="pinterest_url" value="{{ $themeSettings['pinterest_url'] ?? '' }}" placeholder="https://pinterest.com/yourhandle" class="mt-1 block w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent text-sm">
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="show_youtube" id="show_youtube" class="focus:ring-accent h-4 w-4 text-accent border-border-color rounded" {{ ($themeSettings['show_youtube'] ?? true) ? 'checked' : '' }}>
                                </div>
                                <div class="flex-1">
                                    <label for="show_youtube" class="block text-sm font-medium text-primary">YouTube</label>
                                    <input type="url" name="youtube_url" id="youtube_url" value="{{ $themeSettings['youtube_url'] ?? '' }}" placeholder="https://youtube.com/yourchannel" class="mt-1 block w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent text-sm">
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="show_linkedin" id="show_linkedin" class="focus:ring-accent h-4 w-4 text-accent border-border-color rounded" {{ ($themeSettings['show_linkedin'] ?? true) ? 'checked' : '' }}>
                                </div>
                                <div class="flex-1">
                                    <label for="show_linkedin" class="block text-sm font-medium text-primary">LinkedIn</label>
                                    <input type="url" name="linkedin_url" id="linkedin_url" value="{{ $themeSettings['linkedin_url'] ?? '' }}" placeholder="https://linkedin.com/company/yourcompany" class="mt-1 block w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-6 border-t border-border-color flex justify-end space-x-3">
                <a href="{{ route('admin.settings.theme.reset') }}" class="px-4 py-2 border border-border-color rounded-md text-sm font-medium text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Reset to Defaults
                </a>
                <button type="reset" class="px-4 py-2 border border-border-color rounded-md text-sm font-medium text-primary bg-body hover:bg-card focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                    Cancel Changes
                </button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                    Save Theme
                </button>
            </div>
        </form>
    </div>
    @endif
    
    <!-- Categories Tab -->
    @if($activeTab == 'categories')
    <div class="bg-card rounded-lg shadow-sm border border-border-color">
        <div class="flex items-center justify-between p-6 border-b border-border-color">
            <h2 class="text-xl font-medium text-primary">Categories</h2>
            <a href="{{ route('admin.settings.categories.create') }}" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition-colors">
                <i class="fas fa-plus mr-2"></i> Add Category
            </a>
        </div>
        
        <div class="p-6">
            @if(count($categories ?? []) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border-color">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Slug</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Parent</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Products</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-card divide-y divide-border-color">
                            @foreach($categories as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-primary">{{ $category->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-secondary">{{ $category->slug }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-secondary">{{ $category->parent ? $category->parent->name : 'None' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-secondary">{{ $category->products->count() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.settings.categories.edit', $category->id) }}" class="text-accent hover:text-accent/80">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.settings.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                
                                {{-- Show child categories with indentation --}}
                                @if($category->children && $category->children->count() > 0)
                                    @foreach($category->children as $childCategory)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-primary flex items-center">
                                                    <span class="ml-4">└─ {{ $childCategory->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-secondary">{{ $childCategory->slug }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-secondary">{{ $category->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-secondary">{{ $childCategory->products->count() }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('admin.settings.categories.edit', $childCategory->id) }}" class="text-accent hover:text-accent/80">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.settings.categories.destroy', $childCategory->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                        <i class="fas fa-folder-open text-secondary text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-primary">No Categories Found</h3>
                    <p class="text-secondary mt-1">You haven't created any categories yet.</p>
                    <a href="{{ route('admin.settings.categories.create') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition-colors">
                        <i class="fas fa-plus mr-2"></i> Create First Category
                    </a>
                </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Discounts Tab -->
    @if($activeTab == 'discounts')
    <div class="bg-card rounded-lg shadow-sm border border-border-color p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-medium text-primary">Discounts</h2>
            <button type="button" onclick="toggleDiscountForm()" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                <i class="fas fa-plus mr-2"></i> Add Discount
            </button>
        </div>
        
        <!-- Discount Form -->
        <div id="discountForm" class="mb-8 bg-body rounded-lg border border-border-color p-6" style="display: {{ request()->get('action') == 'create' ? 'block' : 'none' }};">
            <h3 class="text-lg font-medium text-primary mb-4">{{ isset($editDiscount) ? 'Edit Discount' : 'New Discount' }}</h3>
            
            <form action="{{ isset($editDiscount) ? route('admin.settings.discounts.update', $editDiscount->id) : route('admin.settings.discounts.store') }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($editDiscount))
                    @method('PUT')
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-primary mb-1">Discount Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ $editDiscount->name ?? old('name') }}" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="code" class="block text-sm font-medium text-primary mb-1">Discount Code <span class="text-red-500">*</span></label>
                        <input type="text" name="code" id="code" value="{{ $editDiscount->code ?? old('code') }}" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent" required>
                        <p class="mt-1 text-xs text-secondary">Customers will enter this code at checkout</p>
                        @error('code')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="type" class="block text-sm font-medium text-primary mb-1">Discount Type <span class="text-red-500">*</span></label>
                        <select name="type" id="type" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent" required>
                            <option value="percentage" {{ (isset($editDiscount) && $editDiscount->type == 'percentage') || old('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                            <option value="fixed_amount" {{ (isset($editDiscount) && $editDiscount->type == 'fixed_amount') || old('type') == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                            <option value="free_shipping" {{ (isset($editDiscount) && $editDiscount->type == 'free_shipping') || old('type') == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="value" class="block text-sm font-medium text-primary mb-1">Discount Value</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm" id="discountSymbol">%</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="value" id="value" value="{{ $editDiscount->value ?? old('value') }}" class="w-full pl-7 pr-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                        </div>
                        <p class="mt-1 text-xs text-secondary">Amount/percentage to discount</p>
                        @error('value')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="min_order_amount" class="block text-sm font-medium text-primary mb-1">Minimum Order Amount</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="min_order_amount" id="min_order_amount" value="{{ $editDiscount->min_order_amount ?? old('min_order_amount') }}" class="w-full pl-7 pr-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                        </div>
                        <p class="mt-1 text-xs text-secondary">Minimum purchase amount required</p>
                        @error('min_order_amount')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-primary mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent" required>
                            <option value="active" {{ (isset($editDiscount) && $editDiscount->status == 'active') || old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ (isset($editDiscount) && $editDiscount->status == 'inactive') || old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-border-color">
                    <button type="button" onclick="toggleDiscountForm()" class="px-4 py-2 border border-border-color rounded-md text-sm font-medium text-primary bg-body hover:bg-card focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                        {{ isset($editDiscount) ? 'Update Discount' : 'Create Discount' }}
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Discounts List -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border-color">
                <thead class="bg-body">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Code</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Value</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-card divide-y divide-border-color">
                    @forelse($discounts ?? [] as $discount)
                    <tr class="hover:bg-body transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-primary">{{ $discount->code }}</div>
                            <div class="text-sm text-secondary">{{ $discount->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                            @if($discount->type == 'percentage')
                                Percentage
                            @elseif($discount->type == 'fixed_amount')
                                Fixed Amount
                            @else
                                Free Shipping
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                            @if($discount->type == 'percentage')
                                {{ $discount->value }}%
                            @elseif($discount->type == 'fixed_amount')
                                ${{ number_format($discount->value, 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($discount->status == 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">Active</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.settings', ['tab' => 'discounts', 'action' => 'edit', 'id' => $discount->id]) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">Edit</a>
                            <form method="POST" action="{{ route('admin.settings.discounts.destroy', $discount->id) }}" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" onclick="return confirm('Are you sure you want to delete this discount?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-secondary">No discounts found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($discounts) && $discounts->hasPages())
        <div class="mt-4">
            {{ $discounts->links() }}
        </div>
        @endif
    </div>
    @endif
</div>

<!-- Script for synchronizing color pickers and text inputs -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Color picker script loaded');
    
    // Get all color picker inputs
    const colorPickers = [
        { picker: document.getElementById('primary_color_picker'), text: document.getElementById('primary_color') },
        { picker: document.getElementById('secondary_color_picker'), text: document.getElementById('secondary_color') },
        { picker: document.getElementById('link_color_picker'), text: document.getElementById('link_color') },
        { picker: document.getElementById('card_link_color_picker'), text: document.getElementById('card_link_color') },
        { picker: document.getElementById('category_link_color_picker'), text: document.getElementById('category_link_color') },
        { picker: document.getElementById('product_link_color_picker'), text: document.getElementById('product_link_color') },
        { picker: document.getElementById('body_bg_color_picker'), text: document.getElementById('body_bg_color') },
        { picker: document.getElementById('card_bg_color_picker'), text: document.getElementById('card_bg_color') },
        { picker: document.getElementById('text_color_picker'), text: document.getElementById('text_color') },
        { picker: document.getElementById('cart_badge_bg_color_picker'), text: document.getElementById('cart_badge_bg_color') }
    ].filter(item => item.picker && item.text); // Filter out any that don't exist
    
    console.log('Found color pickers:', colorPickers.length);
    
    // Set up event listeners for each color picker
    colorPickers.forEach(function(item) {
        console.log('Setting up listeners for:', item.text.name);
        
        // When the color picker changes, update the text input
        item.picker.addEventListener('input', function(e) {
            console.log(`Color picker ${item.text.name} changed to ${this.value}`);
            item.text.value = this.value.toUpperCase();
        });
        
        // When the text input changes, update the color picker
        item.text.addEventListener('input', function(e) {
            console.log(`Text input ${this.name} changed to ${this.value}`);
            item.picker.value = this.value;
        });
        
        // Initially ensure the text input has the value of the color picker
        if (item.picker.value) {
            item.text.value = item.picker.value.toUpperCase();
            console.log(`Initialized ${item.text.name} to ${item.text.value}`);
        }
    });
    
    // Handle form submission
    const themeForm = document.querySelector('form[action*="settings.update"]');
    if (themeForm) {
        console.log('Theme form found');
        themeForm.addEventListener('submit', function(e) {
            // Ensure all color inputs have values
            colorPickers.forEach(function(item) {
                if (item.text.value === '') {
                    console.log(`Empty value detected for ${item.text.name}, using color picker value`);
                    item.text.value = item.picker.value.toUpperCase();
                }
                console.log(`Final ${item.text.name}: ${item.text.value}`);
            });
            
            console.log('Form submitted with color values:');
            colorPickers.forEach(function(item) {
                console.log(`${item.text.name}: ${item.text.value}`);
            });
        });
    } else {
        console.error('Theme form not found');
    }
});
</script>

<script>
    function toggleDiscountForm() {
        const form = document.getElementById('discountForm');
        if (form.style.display === 'none') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
    
    // Update discount symbol based on type
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const valueSymbol = document.getElementById('discountSymbol');
        
        function updateSymbol() {
            if (typeSelect.value === 'percentage') {
                valueSymbol.textContent = '%';
            } else if (typeSelect.value === 'fixed_amount') {
                valueSymbol.textContent = '$';
            } else {
                valueSymbol.textContent = '';
            }
        }
        
        // Set initial state
        updateSymbol();
        
        // Update on change
        typeSelect.addEventListener('change', updateSymbol);
    });
</script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabs = document.querySelectorAll('.theme-tab');
    const tabContents = document.querySelectorAll('.theme-tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs and contents
            tabs.forEach(t => {
                t.classList.remove('active', 'border-accent', 'text-accent', 'bg-accent/5');
                t.classList.add('border-transparent', 'text-primary');
            });
            tabContents.forEach(c => c.classList.add('hidden'));

            // Add active class to clicked tab
            tab.classList.add('active', 'border-accent', 'text-accent', 'bg-accent/5');
            tab.classList.remove('border-transparent', 'text-primary');
            
            // Show corresponding content
            const tabId = tab.getAttribute('data-tab');
            document.getElementById(`${tabId}-tab`).classList.remove('hidden');
        });
    });

    // Color picker synchronization
    const colorPickers = document.querySelectorAll('input[type="color"]');
    colorPickers.forEach(picker => {
        const textInput = document.getElementById(picker.id.replace('_picker', ''));
        
        picker.addEventListener('input', () => {
            textInput.value = picker.value;
        });

        textInput.addEventListener('input', () => {
            picker.value = textInput.value;
        });
    });
});
</script>
@endpush

@endsection 