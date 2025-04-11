@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ isset($store) ? 'Edit Store' : 'Add New Store' }}</h1>
        <p class="text-gray-600 mt-1">{{ isset($store) ? 'Update store information and settings' : 'Create a new store in the platform' }}</p>
    </div>
    
    <form action="{{ isset($store) ? route('admin.stores.update', $store->id) : route('admin.stores.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(isset($store))
            @method('PUT')
        @endif
        
        <!-- Store Information -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Store Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Store Name *</label>
                    <input type="text" name="name" id="name" value="{{ $store->name ?? old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" required>
                        <option value="active" {{ (isset($store) && $store->status == 'active') || old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ (isset($store) && $store->status == 'inactive') || old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="pending" {{ (isset($store) && $store->status == 'pending') || old('status') == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Store Description</label>
                <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">{{ $store->description ?? old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Store Logo</label>
                <div class="mt-1 flex items-center">
                    <div class="h-32 w-32 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 bg-gray-100 flex items-center justify-center">
                        @if(isset($store) && $store->logo)
                            <img src="{{ $store->logo }}" alt="{{ $store->name }}" class="h-full w-full object-contain">
                        @else
                            <i class="fas fa-store text-gray-400 text-3xl"></i>
                        @endif
                    </div>
                    <div class="ml-4">
                        <div class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm">
                            <label for="logo" class="text-sm font-medium text-gray-700 cursor-pointer">
                                <span>Upload logo</span>
                                <input type="file" name="logo" id="logo" accept="image/*" class="sr-only">
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, SVG up to 2MB</p>
                    </div>
                </div>
                @error('logo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Domain Configuration -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Domain Configuration</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="domain" class="block text-sm font-medium text-gray-700 mb-1">Store Domain *</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            https://
                        </span>
                        <input type="text" name="domain" id="domain" value="{{ $store->domain ?? old('domain') }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="yourstore.connectcommerce.com" required>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">This will be the web address customers use to access your store.</p>
                    @error('domain')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="custom_domain" class="block text-sm font-medium text-gray-700 mb-1">Custom Domain (Optional)</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            https://
                        </span>
                        <input type="text" name="custom_domain" id="custom_domain" value="{{ $store->custom_domain ?? old('custom_domain') }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="www.yourstore.com">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">If you have your own domain, enter it here. Additional DNS configuration will be required.</p>
                    @error('custom_domain')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Owner Information -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Owner Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">Owner Name *</label>
                    <input type="text" name="owner_name" id="owner_name" value="{{ $store->owner_name ?? old('owner_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" required>
                    @error('owner_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="owner_email" class="block text-sm font-medium text-gray-700 mb-1">Owner Email *</label>
                    <input type="email" name="owner_email" id="owner_email" value="{{ $store->owner_email ?? old('owner_email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" required>
                    @error('owner_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="owner_phone" class="block text-sm font-medium text-gray-700 mb-1">Owner Phone</label>
                    <input type="text" name="owner_phone" id="owner_phone" value="{{ $store->owner_phone ?? old('owner_phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    @error('owner_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Associated User</label>
                    <select name="user_id" id="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        <option value="">Select a user</option>
                        @if(isset($users))
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (isset($store) && $store->user_id == $user->id) || old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Link this store to an existing user account</p>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="send_welcome_email" id="send_welcome_email" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ old('send_welcome_email', true) ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="send_welcome_email" class="font-medium text-gray-700">Send welcome email</label>
                        <p class="text-gray-500">Send store owner an email with setup instructions.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Plan & Features -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Plan & Features</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-1">Subscription Plan *</label>
                    <select name="plan_id" id="plan_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" required>
                        <option value="1" {{ (isset($store) && $store->plan_id == 1) || old('plan_id') == 1 ? 'selected' : '' }}>Basic - $29/month</option>
                        <option value="2" {{ (isset($store) && $store->plan_id == 2) || old('plan_id') == 2 ? 'selected' : '' }}>Professional - $79/month</option>
                        <option value="3" {{ (isset($store) && $store->plan_id == 3) || old('plan_id') == 3 ? 'selected' : '' }}>Business - $299/month</option>
                        <option value="4" {{ (isset($store) && $store->plan_id == 4) || old('plan_id') == 4 ? 'selected' : '' }}>Enterprise - Custom</option>
                    </select>
                    @error('plan_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="trial_ends_at" class="block text-sm font-medium text-gray-700 mb-1">Trial End Date</label>
                    <input type="date" name="trial_ends_at" id="trial_ends_at" value="{{ isset($store) && $store->trial_ends_at ? $store->trial_ends_at->format('Y-m-d') : old('trial_ends_at') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Leave empty for no trial period</p>
                    @error('trial_ends_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                <div class="mt-1 space-y-3">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="features[]" value="custom_domain" id="feature_custom_domain" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ isset($store) && in_array('custom_domain', $store->features ?? []) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="feature_custom_domain" class="font-medium text-gray-700">Custom Domain</label>
                            <p class="text-gray-500">Allow store to use their own domain name.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="features[]" value="api_access" id="feature_api_access" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ isset($store) && in_array('api_access', $store->features ?? []) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="feature_api_access" class="font-medium text-gray-700">API Access</label>
                            <p class="text-gray-500">Allow store to use the API for integrations.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="features[]" value="advanced_analytics" id="feature_advanced_analytics" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ isset($store) && in_array('advanced_analytics', $store->features ?? []) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="feature_advanced_analytics" class="font-medium text-gray-700">Advanced Analytics</label>
                            <p class="text-gray-500">Enable advanced analytics and reporting features.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.stores') }}" class="px-5 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ isset($store) ? 'Update Store' : 'Create Store' }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Preview uploaded logo
    document.getElementById('logo').addEventListener('change', function(event) {
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