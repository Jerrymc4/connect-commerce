@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Store Settings</h1>
    
    <div class="mb-6">
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Configure your store settings to customize your shopping experience.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- General Settings -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">General Settings</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="store_name" class="block text-sm font-medium text-gray-700 mb-1">Store Name *</label>
                    <input type="text" name="store_name" id="store_name" value="{{ $settings->store_name ?? old('store_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" required>
                    @error('store_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="store_email" class="block text-sm font-medium text-gray-700 mb-1">Store Email *</label>
                    <input type="email" name="store_email" id="store_email" value="{{ $settings->store_email ?? old('store_email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" required>
                    @error('store_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="store_phone" class="block text-sm font-medium text-gray-700 mb-1">Store Phone</label>
                    <input type="text" name="store_phone" id="store_phone" value="{{ $settings->store_phone ?? old('store_phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    @error('store_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="store_address" class="block text-sm font-medium text-gray-700 mb-1">Store Address</label>
                    <input type="text" name="store_address" id="store_address" value="{{ $settings->store_address ?? old('store_address') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    @error('store_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label for="store_description" class="block text-sm font-medium text-gray-700 mb-1">Store Description</label>
                <textarea name="store_description" id="store_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">{{ $settings->store_description ?? old('store_description') }}</textarea>
                @error('store_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Store Logo</label>
                <div class="mt-1 flex items-center">
                    <div class="h-32 w-32 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 bg-gray-100 flex items-center justify-center">
                        @if(isset($settings) && $settings->store_logo)
                            <img src="{{ $settings->store_logo }}" alt="Store Logo" class="h-full w-full object-contain">
                        @else
                            <i class="fas fa-store text-gray-400 text-3xl"></i>
                        @endif
                    </div>
                    <div class="ml-4">
                        <div class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm">
                            <label for="store_logo" class="text-sm font-medium text-gray-700 cursor-pointer">
                                <span>Upload logo</span>
                                <input type="file" name="store_logo" id="store_logo" accept="image/*" class="sr-only">
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, SVG up to 2MB</p>
                    </div>
                </div>
                @error('store_logo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Regional Settings -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Regional Settings</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Currency *</label>
                    <select name="currency" id="currency" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" required>
                        <option value="USD" {{ (isset($settings) && $settings->currency == 'USD') || old('currency') == 'USD' ? 'selected' : '' }}>US Dollar ($)</option>
                        <option value="EUR" {{ (isset($settings) && $settings->currency == 'EUR') || old('currency') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                        <option value="GBP" {{ (isset($settings) && $settings->currency == 'GBP') || old('currency') == 'GBP' ? 'selected' : '' }}>British Pound (£)</option>
                        <option value="CAD" {{ (isset($settings) && $settings->currency == 'CAD') || old('currency') == 'CAD' ? 'selected' : '' }}>Canadian Dollar (C$)</option>
                        <option value="AUD" {{ (isset($settings) && $settings->currency == 'AUD') || old('currency') == 'AUD' ? 'selected' : '' }}>Australian Dollar (A$)</option>
                    </select>
                    @error('currency')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="weight_unit" class="block text-sm font-medium text-gray-700 mb-1">Weight Unit</label>
                    <select name="weight_unit" id="weight_unit" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        <option value="kg" {{ (isset($settings) && $settings->weight_unit == 'kg') || old('weight_unit') == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                        <option value="g" {{ (isset($settings) && $settings->weight_unit == 'g') || old('weight_unit') == 'g' ? 'selected' : '' }}>Grams (g)</option>
                        <option value="lb" {{ (isset($settings) && $settings->weight_unit == 'lb') || old('weight_unit') == 'lb' ? 'selected' : '' }}>Pounds (lb)</option>
                        <option value="oz" {{ (isset($settings) && $settings->weight_unit == 'oz') || old('weight_unit') == 'oz' ? 'selected' : '' }}>Ounces (oz)</option>
                    </select>
                    @error('weight_unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="dimension_unit" class="block text-sm font-medium text-gray-700 mb-1">Dimension Unit</label>
                    <select name="dimension_unit" id="dimension_unit" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        <option value="cm" {{ (isset($settings) && $settings->dimension_unit == 'cm') || old('dimension_unit') == 'cm' ? 'selected' : '' }}>Centimeters (cm)</option>
                        <option value="m" {{ (isset($settings) && $settings->dimension_unit == 'm') || old('dimension_unit') == 'm' ? 'selected' : '' }}>Meters (m)</option>
                        <option value="in" {{ (isset($settings) && $settings->dimension_unit == 'in') || old('dimension_unit') == 'in' ? 'selected' : '' }}>Inches (in)</option>
                        <option value="ft" {{ (isset($settings) && $settings->dimension_unit == 'ft') || old('dimension_unit') == 'ft' ? 'selected' : '' }}>Feet (ft)</option>
                    </select>
                    @error('dimension_unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Email Settings -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Email Settings</h2>
            
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="send_order_emails" id="send_order_emails" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ (isset($settings) && $settings->send_order_emails) || old('send_order_emails') ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="send_order_emails" class="font-medium text-gray-700">Send Order Confirmation Emails</label>
                        <p class="text-gray-500">Send an email to customers when they place an order.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="send_shipping_emails" id="send_shipping_emails" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ (isset($settings) && $settings->send_shipping_emails) || old('send_shipping_emails') ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="send_shipping_emails" class="font-medium text-gray-700">Send Shipping Notification Emails</label>
                        <p class="text-gray-500">Send an email to customers when their order ships.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="send_inventory_emails" id="send_inventory_emails" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ (isset($settings) && $settings->send_inventory_emails) || old('send_inventory_emails') ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="send_inventory_emails" class="font-medium text-gray-700">Send Low Inventory Alerts</label>
                        <p class="text-gray-500">Receive emails when product inventory is low.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
            <button type="reset" class="px-5 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Reset
            </button>
            <button type="submit" class="px-5 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection 