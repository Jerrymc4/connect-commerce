@extends('layouts.store')

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
            <a href="{{ route('store.settings', ['tab' => 'general'], false) }}" class="inline-block py-4 px-4 text-center border-b-2 {{ $activeTab == 'general' ? 'border-accent text-accent font-medium' : 'border-transparent hover:text-primary hover:border-border-color' }}">
                <i class="fas fa-cog mr-2"></i> General
            </a>
            <a href="{{ route('store.settings', ['tab' => 'theme'], false) }}" class="inline-block py-4 px-4 text-center border-b-2 {{ $activeTab == 'theme' ? 'border-accent text-accent font-medium' : 'border-transparent hover:text-primary hover:border-border-color' }}">
                <i class="fas fa-paint-brush mr-2"></i> Theme
            </a>
            <a href="{{ route('store.settings', ['tab' => 'discounts'], false) }}" class="inline-block py-4 px-4 text-center border-b-2 {{ $activeTab == 'discounts' ? 'border-accent text-accent font-medium' : 'border-transparent hover:text-primary hover:border-border-color' }}">
                <i class="fas fa-tag mr-2"></i> Discounts
            </a>
        </div>
    </div>
    
    <!-- General Settings Tab -->
    @if($activeTab == 'general')
    <div class="bg-card rounded-lg shadow-sm border border-border-color p-6">
        <form action="{{ route('store.settings.update') }}" method="POST" class="space-y-8">
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
        <form action="{{ route('store.settings.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            <input type="hidden" name="section" value="theme_settings">
            
            <!-- Colors -->
            <div>
                <h2 class="text-xl font-medium text-primary mb-4">Colors</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="colors[primary]" class="block text-sm font-medium text-primary mb-1">Primary Color</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-border-color bg-body">
                                <input type="color" name="colors[primary]" id="colors[primary]" value="{{ $themeSettings['colors']['primary'] ?? '#3B82F6' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                            </span>
                            <input type="text" name="colors[primary]" value="{{ $themeSettings['colors']['primary'] ?? '#3B82F6' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-border-color bg-input text-primary focus:ring-accent focus:border-accent">
                        </div>
                        <p class="mt-1 text-xs text-secondary">Used for buttons, links, and accents</p>
                        @error('colors.primary')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="colors[secondary]" class="block text-sm font-medium text-primary mb-1">Secondary Color</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-border-color bg-body">
                                <input type="color" name="colors[secondary]" id="colors[secondary]" value="{{ $themeSettings['colors']['secondary'] ?? '#10B981' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                            </span>
                            <input type="text" name="colors[secondary]" value="{{ $themeSettings['colors']['secondary'] ?? '#10B981' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-border-color bg-input text-primary focus:ring-accent focus:border-accent">
                        </div>
                        <p class="mt-1 text-xs text-secondary">Used for secondary elements</p>
                        @error('colors.secondary')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="colors[accent]" class="block text-sm font-medium text-primary mb-1">Accent Color</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-border-color bg-body">
                                <input type="color" name="colors[accent]" id="colors[accent]" value="{{ $themeSettings['colors']['accent'] ?? '#8B5CF6' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                            </span>
                            <input type="text" name="colors[accent]" value="{{ $themeSettings['colors']['accent'] ?? '#8B5CF6' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-border-color bg-input text-primary focus:ring-accent focus:border-accent">
                        </div>
                        <p class="mt-1 text-xs text-secondary">Used for highlights and accents</p>
                        @error('colors.accent')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="colors[background]" class="block text-sm font-medium text-primary mb-1">Background Color</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-border-color bg-body">
                                <input type="color" name="colors[background]" id="colors[background]" value="{{ $themeSettings['colors']['background'] ?? '#FFFFFF' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                            </span>
                            <input type="text" name="colors[background]" value="{{ $themeSettings['colors']['background'] ?? '#FFFFFF' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-border-color bg-input text-primary focus:ring-accent focus:border-accent">
                        </div>
                        <p class="mt-1 text-xs text-secondary">Used for page backgrounds</p>
                        @error('colors.background')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Typography -->
            <div class="pt-6 border-t border-border-color">
                <h2 class="text-xl font-medium text-primary mb-4">Typography</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="typography[heading_font]" class="block text-sm font-medium text-primary mb-1">Heading Font</label>
                        <select name="typography[heading_font]" id="typography[heading_font]" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                            @foreach($availableFonts as $fontName => $fontDisplay)
                            <option value="{{ $fontName }}" {{ ($themeSettings['typography']['heading_font'] ?? '') == $fontName ? 'selected' : '' }}>{{ $fontDisplay }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-secondary">Used for headings and titles</p>
                        @error('typography.heading_font')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="typography[body_font]" class="block text-sm font-medium text-primary mb-1">Body Font</label>
                        <select name="typography[body_font]" id="typography[body_font]" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                            @foreach($availableFonts as $fontName => $fontDisplay)
                            <option value="{{ $fontName }}" {{ ($themeSettings['typography']['body_font'] ?? '') == $fontName ? 'selected' : '' }}>{{ $fontDisplay }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-secondary">Used for regular text</p>
                        @error('typography.body_font')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="typography[base_size]" class="block text-sm font-medium text-primary mb-1">Base Font Size</label>
                        <select name="typography[base_size]" id="typography[base_size]" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent">
                            <option value="14px" {{ ($themeSettings['typography']['base_size'] ?? '') == '14px' ? 'selected' : '' }}>Small (14px)</option>
                            <option value="16px" {{ ($themeSettings['typography']['base_size'] ?? '') == '16px' ? 'selected' : '' }}>Medium (16px)</option>
                            <option value="18px" {{ ($themeSettings['typography']['base_size'] ?? '') == '18px' ? 'selected' : '' }}>Large (18px)</option>
                        </select>
                        <p class="mt-1 text-xs text-secondary">Base size for text</p>
                        @error('typography.base_size')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Custom CSS -->
            <div class="pt-6 border-t border-border-color">
                <h2 class="text-xl font-medium text-primary mb-4">Custom CSS</h2>
                <div>
                    <label for="custom_css" class="block text-sm font-medium text-primary mb-1">Custom CSS</label>
                    <textarea name="custom_css" id="custom_css" rows="6" class="w-full px-3 py-2 border border-border-color rounded-md bg-input text-primary shadow-sm focus:ring-accent focus:border-accent font-mono">{{ $themeSettings['custom_css'] ?? '' }}</textarea>
                    <p class="mt-1 text-xs text-secondary">Add custom CSS to further customize your store's appearance</p>
                    @error('custom_css')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="pt-6 border-t border-border-color flex justify-end space-x-3">
                <button type="reset" class="px-4 py-2 border border-border-color rounded-md text-sm font-medium text-primary bg-body hover:bg-card focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                    Reset
                </button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                    Save Theme
                </button>
            </div>
        </form>
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
            
            <form action="{{ isset($editDiscount) ? route('store.settings.discounts.update', $editDiscount->id) : route('store.settings.discounts.store') }}" method="POST" class="space-y-6">
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
                            <a href="{{ route('store.settings', ['tab' => 'discounts', 'action' => 'edit', 'id' => $discount->id]) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">Edit</a>
                            <form method="POST" action="{{ route('store.settings.discounts.destroy', $discount->id) }}" class="inline-block">
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

@if($activeTab == 'discounts')
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
@endif
@endsection 