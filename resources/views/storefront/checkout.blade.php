@extends('layouts.storefront')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-extrabold tracking-tight text-primary mb-8">Checkout</h1>
            
            @if(session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <form action="{{ route('storefront.checkout.process') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8">
                        <div>
                            <h2 class="text-lg font-medium text-primary mb-6">Contact & Shipping Information</h2>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">First name</label>
                                    <input type="text" name="first_name" id="first_name" value="{{ $customer->first_name ?? old('first_name') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('first_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last name</label>
                                    <input type="text" name="last_name" id="last_name" value="{{ $customer->last_name ?? old('last_name') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                                <input type="email" name="email" id="email" value="{{ $customer->email ?? old('email') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone number</label>
                                <input type="text" name="phone" id="phone" value="{{ $customer->phone ?? old('phone') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700">Street address</label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" name="city" id="city" value="{{ old('city') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="state" class="block text-sm font-medium text-gray-700">State / Province</label>
                                    <input type="text" name="state" id="state" value="{{ old('state') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('state')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal code</label>
                                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('postal_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                    <select id="country" name="country" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="United States">United States</option>
                                        <option value="Canada">Canada</option>
                                        <option value="Mexico">Mexico</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                    </select>
                                    @error('country')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mt-8">
                                <h2 class="text-lg font-medium text-primary mb-6">Payment Method</h2>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input id="payment_method_cc" name="payment_method" type="radio" value="credit_card" checked class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <label for="payment_method_cc" class="ml-3 block text-sm font-medium text-gray-700">Credit Card</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="payment_method_paypal" name="payment_method" type="radio" value="paypal" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <label for="payment_method_paypal" class="ml-3 block text-sm font-medium text-gray-700">PayPal</label>
                                    </div>
                                </div>
                                
                                <div id="credit_card_details" class="mt-4">
                                    <div class="mb-4">
                                        <label for="card_number" class="block text-sm font-medium text-gray-700">Card Number</label>
                                        <input type="text" name="card_number" id="card_number" placeholder="**** **** **** ****" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                    
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="col-span-2">
                                            <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                            <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YY" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                                            <input type="text" name="cvv" id="cvv" placeholder="***" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h2 class="text-lg font-medium text-primary mb-6">Order Summary</h2>
                            
                            <div class="bg-gray-50 rounded-md p-4 mb-6">
                                @foreach($cart as $id => $item)
                                <div class="flex py-3 border-b border-gray-200 last:border-b-0">
                                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                        @if($item['image'])
                                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover object-center">
                                        @else
                                            <div class="h-full w-full bg-gray-200 flex items-center justify-center text-gray-500">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="ml-4 flex flex-1 flex-col">
                                        <div>
                                            <div class="flex justify-between text-base font-medium text-gray-900">
                                                <h3>{{ $item['name'] }}</h3>
                                                <p class="ml-4">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-1 items-end justify-between text-sm">
                                            <p class="text-gray-500">Qty {{ $item['quantity'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="border-t border-gray-200 pt-6 pb-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <p class="text-gray-600">Subtotal</p>
                                    <p class="text-gray-900">${{ number_format($subtotal, 2) }}</p>
                                </div>
                                <div class="flex justify-between text-sm mb-2">
                                    <p class="text-gray-600">Shipping</p>
                                    <p class="text-gray-900">${{ number_format($shipping, 2) }}</p>
                                </div>
                                <div class="flex justify-between text-sm mb-2">
                                    <p class="text-gray-600">Tax</p>
                                    <p class="text-gray-900">${{ number_format($tax, 2) }}</p>
                                </div>
                                <div class="flex justify-between text-base font-medium mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-primary">Total</p>
                                    <p class="text-primary">${{ number_format($total, 2) }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <button type="submit" class="w-full flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2" style="background-color: var(--primary-color)">
                                    Place Order
                                </button>
                            </div>
                            
                            <div class="mt-4 flex justify-center">
                                <a href="{{ route('storefront.cart') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Back to Cart
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const creditCardRadio = document.getElementById('payment_method_cc');
        const paypalRadio = document.getElementById('payment_method_paypal');
        const creditCardDetails = document.getElementById('credit_card_details');
        
        // Toggle credit card form based on payment method selection
        creditCardRadio.addEventListener('change', function() {
            if (this.checked) {
                creditCardDetails.style.display = 'block';
            }
        });
        
        paypalRadio.addEventListener('change', function() {
            if (this.checked) {
                creditCardDetails.style.display = 'none';
            }
        });
    });
</script>
@endpush 