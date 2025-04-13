                    <!-- Product Info -->
                <div class="flex flex-col">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    
                    <div class="mb-4">
                        @if($product->isOnSale())
                            <div class="flex items-center">
                                <span class="text-3xl font-bold text-red-600">${{ number_format($product->sale_price, 2) }}</span>
                                <span class="ml-2 text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                                <span class="ml-2 bg-red-100 text-red-800 text-xs px-2 py-1 rounded">SALE</span>
                            </div>
                        @else
                            <span class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                    
                    @if($product->sku)
                    <div class="mb-4 text-sm text-gray-500">
                        SKU: {{ $product->sku }}
                    </div>
                    @endif
                    
                    <div class="mb-6">
                        <div class="flex items-center mb-2">
                            <span class="font-semibold text-gray-800 mr-2">Availability:</span>
                            @if(!$product->isOutOfStock())
                                <span class="text-green-600">In Stock</span>
                            @else
                                <span class="text-red-600">Out of Stock</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <p class="text-gray-700">
                            {{ $product->description }}
                        </p>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="flex items-center mb-4">
                            <div class="mr-4">
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                <input type="number" id="quantity" name="quantity" min="1" value="1" 
                                    class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>
                        
                        <div class="flex space-x-4">
                            <button type="button" class="flex-grow bg-purple-600 text-white px-6 py-3 rounded-md font-medium hover:bg-purple-700 transition duration-200 flex items-center justify-center {{ $product->isOutOfStock() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $product->isOutOfStock() ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                            </button>
                            
                            <button type="button" class="bg-gray-100 text-gray-700 px-3 py-3 rounded-md hover:bg-gray-200 transition duration-200">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div> 