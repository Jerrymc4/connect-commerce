<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Theme CSS File -->
    <link rel="stylesheet" href="{{ asset('storage/tenant-' . tenant()->id . '/theme/theme.css') }}">
    
    <!-- Theme CSS Variables -->
    <style>
        :root {
            --primary-color: {{ $themeSettings['primary_color'] ?? '#3B82F6' }};
            --button-bg-color: {{ $themeSettings['button_bg_color'] ?? '#3B82F6' }};
            --button-text-color: {{ $themeSettings['button_text_color'] ?? '#FFFFFF' }};
            --footer-bg-color: {{ $themeSettings['footer_bg_color'] ?? '#1F2937' }};
            --navbar-text-color: {{ $themeSettings['navbar_text_color'] ?? '#111827' }};
            --cart-badge-bg-color: {{ $themeSettings['cart_badge_bg_color'] ?? '#EF4444' }};
            --body-bg-color: {{ $themeSettings['body_bg_color'] ?? '#F9FAFB' }};
            --font-family: {{ $themeSettings['font_family'] ?? 'Inter, sans-serif' }};
            --link-color: {{ $themeSettings['link_color'] ?? '#2563EB' }};
            --card-bg-color: {{ $themeSettings['card_bg_color'] ?? '#FFFFFF' }};
            --border-radius: {{ $themeSettings['border_radius'] ?? '0.375rem' }};
        }
        
        body {
            font-family: var(--font-family);
            background-color: var(--body-bg-color);
        }
        
        .btn-primary {
            background-color: var(--button-bg-color);
            color: var(--button-text-color);
        }
        
        a:not(.btn):not(.nav-link) {
            color: var(--link-color);
        }
        
        .card {
            background-color: var(--card-bg-color);
            border-radius: var(--border-radius);
        }
        
        header nav a {
            color: var(--navbar-text-color);
        }
        
        footer.bg-gray-800 {
            background-color: var(--footer-bg-color);
        }
        
        .cart-count {
            background-color: var(--cart-badge-bg-color, var(--primary-color));
        }
    </style>
    
    <!-- Custom styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased h-full bg-gray-50 text-gray-900">
    <div class="min-h-full flex flex-col">
        <!-- Header - Only show on non-login/register pages -->
        @if(!Route::is('customer.login') && !Route::is('customer.register') && !Route::is('customer.password.request'))
        <header class="bg-white shadow-sm">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('storefront.home') }}" class="font-bold text-xl text-purple-600">
                            @if(!empty($themeSettings['logo_url']))
                                <img src="{{ Storage::url($themeSettings['logo_url']) }}" alt="{{ $storeName ?? tenant()->name ?? config('app.name') }}" class="h-10 w-auto">
                            @else
                                {{ $storeName ?? tenant()->name ?? config('app.name') }}
                            @endif
                        </a>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="hidden md:flex space-x-8">
                        <a href="{{ route('storefront.home') }}" class="text-gray-600 hover:text-gray-900">Home</a>
                        <a href="{{ route('storefront.products.index') }}" class="text-gray-600 hover:text-gray-900">Products</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900">Categories</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900">About</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900">Contact</a>
                    </nav>
                    
                    <!-- Right side icons -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <button type="button" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-search"></i>
                        </button>
                        
                        <!-- Cart icon (always visible) -->
                        <a href="{{ route('storefront.cart') }}" class="text-gray-700 hover:text-gray-900 relative">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center cart-count">0</span>
                        </a>
                        
                        <!-- Profile section - conditional display -->
                        @auth('customer')
                            <!-- User is logged in - show profile dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-gray-700 hover:text-gray-900 focus:outline-none">
                                    <span class="mr-1">{{ Auth::guard('customer')->user()->name }}</span>
                                    <i class="fas fa-user-circle text-xl"></i>
                                    <i class="fas fa-chevron-down text-xs ml-1"></i>
                                </button>
                                
                                <!-- Dropdown menu -->
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="{{ route('customer.account') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Account</a>
                                    <a href="{{ route('customer.orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Orders</a>
                                    <a href="{{ route('customer.wishlist') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Wishlist</a>
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="/customer/logout">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- User is not logged in - show login/register links -->
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('customer.login') }}" class="text-gray-700 hover:text-gray-900">
                                    <span>Sign In</span>
                                </a>
                                <span class="text-gray-400">|</span>
                                <a href="{{ route('customer.register') }}" class="text-gray-700 hover:text-gray-900">
                                    <span>Register</span>
                                </a>
                            </div>
                        @endauth
                        
                        <!-- Mobile menu button -->
                        <button type="button" class="md:hidden text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>
        @else
        <!-- Minimal header for login/register pages -->
        <div class="py-6 bg-white shadow-sm">
            <div class="container mx-auto px-4">
                <a href="{{ route('storefront.home') }}" class="font-bold text-xl text-purple-600">
                    @if(!empty($themeSettings['logo_url']))
                        <img src="{{ Storage::url($themeSettings['logo_url']) }}" alt="{{ $storeName ?? tenant()->name ?? config('app.name') }}" class="h-10 w-auto">
                    @else
                        {{ $storeName ?? tenant()->name ?? config('app.name') }}
                    @endif
                </a>
            </div>
        </div>
        @endif
        
        <!-- Banner (only show on non-login/register pages) -->
        @if(!Route::is('customer.login') && !Route::is('customer.register') && !Route::is('customer.password.request'))
            @if(!empty($themeSettings['banner_image']) || !empty($themeSettings['banner_text']))
            <div class="bg-gradient-to-r from-blue-800 to-indigo-900 text-white py-12">
                <div class="container mx-auto px-4">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <div class="mb-6 md:mb-0 md:w-1/2">
                            @if(!empty($themeSettings['banner_text']))
                            <h2 class="text-3xl font-bold mb-4">{{ $themeSettings['banner_text'] }}</h2>
                            @endif
                            <p class="text-blue-100 mb-6">Discover amazing products at great prices</p>
                            <a href="{{ route('storefront.products.index') }}" class="inline-block bg-white text-blue-900 px-6 py-3 rounded-lg font-semibold hover:bg-blue-100 transition-colors">
                                Shop Now
                            </a>
                        </div>
                        @if(!empty($themeSettings['banner_image']))
                        <div class="md:w-1/2">
                            <img src="{{ Storage::url($themeSettings['banner_image']) }}" alt="Banner" class="rounded-lg shadow-lg w-full">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        @endif
        
        <!-- Main content -->
        <main class="flex-grow">
            @yield('content')
        </main>
        
        <!-- Footer -->
        @if(!Route::is('customer.login') && !Route::is('customer.register') && !Route::is('customer.password.request'))
        <footer class="bg-gray-800 text-white py-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-xl font-semibold mb-4">{{ $storeName ?? tenant()->name ?? config('app.name') }}</h3>
                        <p class="text-gray-400 mb-4">Your one-stop shop for quality products at affordable prices.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-pinterest"></i></a>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Shop</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('storefront.products.index') }}" class="text-gray-400 hover:text-white">All Products</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">New Arrivals</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Featured</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Sale</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Customer Service</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white">Contact Us</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Shipping & Returns</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Size Guide</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact</h3>
                        <ul class="space-y-2 text-gray-400">
                            <li><i class="fas fa-map-marker-alt mr-2"></i> 123 Main St, Anytown, USA</li>
                            <li><i class="fas fa-phone mr-2"></i> (123) 456-7890</li>
                            <li><i class="fas fa-envelope mr-2"></i> info@{{ tenant()->domain ?? 'example.com' }}</li>
                        </ul>
                    </div>
                </div>
                
                <hr class="border-gray-700 my-8">
                
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} {{ $storeName ?? tenant()->name ?? config('app.name') }}. All rights reserved.
                    </p>
                    <div class="mt-4 md:mt-0">
                        <ul class="flex space-x-6 text-sm text-gray-400">
                            <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                            <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                            <li><a href="#" class="hover:text-white">Refund Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        @else
        <!-- Minimal footer for login/register pages -->
        <footer class="py-4 bg-white border-t">
            <div class="container mx-auto px-4 text-center">
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} {{ $storeName ?? tenant()->name ?? config('app.name') }}. All rights reserved.
                </p>
            </div>
        </footer>
        @endif
    </div>
    
    @stack('scripts')
</body>
</html> 