                            @foreach($products as $product)
                                <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden hover:shadow-md transition duration-300 flex flex-col h-full">
                                    <a href="{{ route('storefront.products.show', $product->slug) }}" class="block relative">
                                        <div class="h-52 bg-gray-100 relative overflow-hidden">
                                            @if($product->image)
                                                <img src="{{ tenant_asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <span class="text-gray-400"><i class="fas fa-box text-4xl"></i></span>
                                                </div>
                                            @endif
                                            
                                            @if($product->isOnSale())
                                                <span class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded">
                                                    SALE
                                                </span>
                                            @endif
                                        </div>
                                    </a>
                                    
                                    <div class="p-4 flex-1 flex flex-col">
                                        <div class="flex-1">
                                            <a href="{{ route('storefront.products.show', $product->slug) }}" class="block">
                                                <h3 class="font-medium text-gray-900 hover:text-purple-600 text-lg">{{ $product->name }}</h3>
                                            </a>
                                            
                                            <p class="text-gray-500 text-sm mt-1 line-clamp-2">
                                                {{ $product->description ?? 'No description available' }}
                                            </p>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <div class="flex items-center mb-3">
                                                @if($product->isOnSale())
                                                    <span class="font-bold text-lg text-red-600">${{ number_format($product->sale_price, 2) }}</span>
                                                    <span class="ml-2 text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                                                @else
                                                    <span class="font-bold text-lg text-gray-900">${{ number_format($product->price, 2) }}</span>
                                                @endif
                                                
                                                @if(!$product->isOutOfStock())
                                                    <span class="ml-auto text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">In Stock</span>
                                                @else
                                                    <span class="ml-auto text-xs px-2 py-1 bg-red-100 text-red-800 rounded-full">Out of Stock</span>
                                                @endif
                                            </div>
                                            
                                            <button type="button" class="w-full bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-purple-700 transition duration-200 flex items-center justify-center {{ $product->isOutOfStock() ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->isOutOfStock() ? 'disabled' : '' }}>
                                                <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach 