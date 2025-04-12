@extends('layouts.store')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ isset($customer) ? 'Edit Customer' : 'Add New Customer' }}
        </h1>
        <a href="{{ route('store.customers', [], false) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Customers
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                <i class="fas fa-user-edit mr-2 text-blue-500"></i>
                {{ isset($customer) ? 'Customer Information' : 'New Customer Information' }}
            </h2>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ isset($customer) ? route('store.customers.update', $customer->id, false) : route('store.customers.store', [], false) }}">
                @csrf
                @if(isset($customer))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-user text-blue-500 mr-2"></i>First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $customer->first_name ?? '') }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 @error('first_name') border-red-500 @enderror" 
                            required placeholder="Enter first name">
                        @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-user text-blue-500 mr-2"></i>Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $customer->last_name ?? '') }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 @error('last_name') border-red-500 @enderror" 
                            required placeholder="Enter last name">
                        @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-envelope text-blue-500 mr-2"></i>Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $customer->email ?? '') }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 @error('email') border-red-500 @enderror" 
                            required placeholder="customer@example.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-lock text-blue-500 mr-2"></i>Password {!! isset($customer) ? '' : '<span class="text-red-500">*</span>' !!}
                        </label>
                        <input type="password" name="password" id="password"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 @error('password') border-red-500 @enderror" 
                            {{ isset($customer) ? '' : 'required' }} placeholder="{{ isset($customer) ? 'Leave blank to keep current password' : 'Enter password' }}">
                        @if(isset($customer))
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leave blank to keep current password</p>
                        @endif
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-lock text-blue-500 mr-2"></i>Confirm Password {!! isset($customer) ? '' : '<span class="text-red-500">*</span>' !!}
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" 
                            {{ isset($customer) ? '' : 'required' }} placeholder="Confirm password">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-phone text-blue-500 mr-2"></i>Phone Number
                        </label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $customer->phone ?? '') }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 @error('phone') border-red-500 @enderror"
                            placeholder="(123) 456-7890">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-start">
                        <span class="pr-3 bg-white dark:bg-gray-800 text-lg font-medium text-gray-900 dark:text-gray-100">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>Address Information
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Address Line 1 -->
                    <div class="md:col-span-2">
                        <label for="address_line_1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-home text-blue-500 mr-2"></i>Street Address
                        </label>
                        <input type="text" name="address_line_1" id="address_line_1" 
                            value="{{ old('address_line_1', isset($customer) && $customer->defaultAddress() ? $customer->defaultAddress()->address_line_1 : '') }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 @error('address_line_1') border-red-500 @enderror"
                            placeholder="123 Main St">
                        @error('address_line_1')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address Line 2 -->
                    <div class="md:col-span-2">
                        <label for="address_line_2" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-building text-blue-500 mr-2"></i>Apartment, Suite, etc.
                        </label>
                        <input type="text" name="address_line_2" id="address_line_2" 
                            value="{{ old('address_line_2', isset($customer) && $customer->defaultAddress() ? $customer->defaultAddress()->address_line_2 : '') }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 @error('address_line_2') border-red-500 @enderror"
                            placeholder="Apt 4B">
                        @error('address_line_2')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-city text-blue-500 mr-2"></i>City
                        </label>
                        <input type="text" name="city" id="city" 
                            value="{{ old('city', isset($customer) && $customer->defaultAddress() ? $customer->defaultAddress()->city : '') }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 @error('city') border-red-500 @enderror"
                            placeholder="New York">
                        @error('city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- State/Province -->
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-map text-blue-500 mr-2"></i>State/Province
                        </label>
                        <input type="text" name="state" id="state" 
                            value="{{ old('state', isset($customer) && $customer->defaultAddress() ? $customer->defaultAddress()->state : '') }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 @error('state') border-red-500 @enderror"
                            placeholder="NY">
                        @error('state')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Postal/Zip Code -->
                    <div>
                        <label for="zipcode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-mail-bulk text-blue-500 mr-2"></i>Postal/Zip Code
                        </label>
                        <input type="text" name="zipcode" id="zipcode" 
                            value="{{ old('zipcode', isset($customer) && $customer->defaultAddress() ? $customer->defaultAddress()->zipcode : '') }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 @error('zipcode') border-red-500 @enderror"
                            placeholder="10001">
                        @error('zipcode')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-globe-americas text-blue-500 mr-2"></i>Country
                        </label>
                        <input type="text" name="country" id="country" 
                            value="{{ old('country', isset($customer) && $customer->defaultAddress() ? $customer->defaultAddress()->country : '') }}" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 @error('country') border-red-500 @enderror"
                            placeholder="United States">
                        @error('country')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3 border-t border-gray-200 dark:border-gray-700 pt-4 mt-6">
                    <a href="{{ route('store.customers', [], false) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                        <i class="fas {{ isset($customer) ? 'fa-save' : 'fa-plus-circle' }} mr-2"></i>
                        {{ isset($customer) ? 'Update Customer' : 'Create Customer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 