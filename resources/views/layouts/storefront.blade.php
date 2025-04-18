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
            border-radius: {{ $themeSettings['button_border_radius'] ?? '6' }}px;
            transition: all 0.3s;
        }
        
        /* Apply button styles based on theme settings */
        .btn-primary, button[type="submit"], .button {
            @if(isset($themeSettings['button_style']))
                @if($themeSettings['button_style'] == 'outline')
                    background-color: transparent;
                    border: 2px solid var(--button-bg-color);
                    color: var(--button-bg-color);
                @elseif($themeSettings['button_style'] == 'flat')
                    background-color: var(--button-bg-color);
                    border: none;
                    box-shadow: none;
                @elseif($themeSettings['button_style'] == '3d')
                    background-color: var(--button-bg-color);
                    border: none;
                    box-shadow: 0 4px 0 rgba(0, 0, 0, 0.2);
                    transform: translateY(0);
                @elseif($themeSettings['button_style'] == 'gradient')
                    background: linear-gradient(to bottom, color-mix(in srgb, var(--button-bg-color) 80%, white), var(--button-bg-color));
                    border: none;
                @else
                    /* Default filled style */
                    background-color: var(--button-bg-color);
                    border: none;
                @endif
            @endif
        }
        
        /* Button hover effects */
        .btn-primary:hover, button[type="submit"]:hover, .button:hover {
            @if(isset($themeSettings['button_hover_effect']))
                @if($themeSettings['button_hover_effect'] == 'darken')
                    filter: brightness(0.85);
                @elseif($themeSettings['button_hover_effect'] == 'lighten')
                    filter: brightness(1.15);
                @elseif($themeSettings['button_hover_effect'] == 'zoom')
                    transform: scale(1.05);
                @elseif($themeSettings['button_hover_effect'] == 'glow')
                    box-shadow: 0 0 8px var(--button-bg-color);
                @else
                    /* No effect */
                @endif
            @endif
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
    @if (session('success'))
    <div id="success-alert" style="background-color: var(--primary-color); color: white; padding: 10px 20px; margin: 0; text-align: center; position: fixed; top: 0; left: 0; right: 0; z-index: 9999; font-weight: bold;">
        {{ session('success') }}
        <button type="button" onclick="closeAlert('success-alert')" style="background: transparent; border: none; color: white; float: right; font-size: 20px; cursor: pointer;">&times;</button>
    </div>
    <script>
        function closeAlert(id) {
            document.getElementById(id).remove();
        }
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            var alert = document.getElementById('success-alert');
            if (alert) alert.remove();
        }, 5000);
    </script>
    @endif
    
    @if (session('error'))
    <div id="error-alert" style="background-color: #FF4136; color: white; padding: 10px 20px; margin: 0; text-align: center; position: fixed; top: 0; left: 0; right: 0; z-index: 9999; font-weight: bold;">
        {{ session('error') }}
        <button type="button" onclick="closeAlert('error-alert')" style="background: transparent; border: none; color: white; float: right; font-size: 20px; cursor: pointer;">&times;</button>
    </div>
    <script>
        function closeAlert(id) {
            document.getElementById(id).remove();
        }
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            var alert = document.getElementById('error-alert');
            if (alert) alert.remove();
        }, 5000);
    </script>
    @endif
    
    <div class="min-h-full flex flex-col">
        <!-- Header - Only show on non-login/register pages -->
        @if(!Route::is('customer.login') && !Route::is('customer.register') && !Route::is('customer.password.request'))
        <header class="bg-white shadow-sm {{ ($themeSettings['sticky_navbar'] ?? true) ? 'sticky top-0 z-50' : '' }}">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('storefront.home') }}" class="flex items-center">
                            @if(!empty($themeSettings['logo_url']))
                                <img src="{{ Storage::url($themeSettings['logo_url']) }}" alt="{{ $storeName ?? tenant()->name ?? config('app.name') }}" class="h-10 w-auto">
                            @else
                                <span class="font-bold text-xl" style="color: var(--navbar-text-color)">{{ $storeName ?? tenant()->name ?? config('app.name') }}</span>
                            @endif
                        </a>
                    </div>
                    
                    <!-- Navigation - Desktop -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('storefront.home') }}" class="font-medium" style="color: var(--navbar-text-color)">Home</a>
                        <a href="{{ route('storefront.products.index') }}" class="font-medium" style="color: var(--navbar-text-color)">Products</a>
                         {{-- <a href="{{ route('storefront.categories') }}" class="font-medium" style="color: var(--navbar-text-color)">Categories</a>  --}}
                        <a href="#" class="font-medium" style="color: var(--navbar-text-color)">About</a>
                        <a href="#" class="font-medium" style="color: var(--navbar-text-color)">Contact</a>
                    </div>
                    
                    <!-- Right side icons -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <button type="button" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-search"></i>
                        </button>
                        
                        <!-- Cart icon -->
                        <a href="{{ route('storefront.cart') }}" class="relative">
                            <i class="fas fa-shopping-cart text-xl" style="color: var(--navbar-text-color)"></i>
                            @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute -top-2 -right-2 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center cart-count">
                                {{ count(session('cart')) }}
                            </span>
                            @endif
                        </a>
                        
                        <!-- Account links -->
                        @auth('customer')
                        <a href="{{ route('customer.account') }}" class="font-medium" style="color: var(--navbar-text-color)">My Account</a>
                        <form action="{{ route('customer.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="font-medium" style="color: var(--navbar-text-color)">Logout</button>
                        </form>
                        @else
                        <a href="{{ route('customer.login') }}" class="font-medium" style="color: var(--navbar-text-color)">Login</a>
                        <a href="{{ route('customer.register') }}" class="font-medium" style="color: var(--navbar-text-color)">Register</a>
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
                            @if($themeSettings['show_facebook'] ?? false)
                            <a href="{{ $themeSettings['facebook_url'] ?? '#' }}" target="_blank" class="text-white/80 hover:text-white" aria-label="Facebook">
                                <i class="fab fa-facebook-f text-xl"></i>
                            </a>
                            @endif
                            
                            @if($themeSettings['show_twitter'] ?? false)
                            <a href="{{ $themeSettings['twitter_url'] ?? '#' }}" target="_blank" class="text-white/80 hover:text-white" aria-label="Twitter">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                            @endif
                            
                            @if($themeSettings['show_instagram'] ?? false)
                            <a href="{{ $themeSettings['instagram_url'] ?? '#' }}" target="_blank" class="text-white/80 hover:text-white" aria-label="Instagram">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                            @endif
                            
                            @if($themeSettings['show_pinterest'] ?? false)
                            <a href="{{ $themeSettings['pinterest_url'] ?? '#' }}" target="_blank" class="text-white/80 hover:text-white" aria-label="Pinterest">
                                <i class="fab fa-pinterest text-xl"></i>
                            </a>
                            @endif
                            
                            @if($themeSettings['show_youtube'] ?? false)
                            <a href="{{ $themeSettings['youtube_url'] ?? '#' }}" target="_blank" class="text-white/80 hover:text-white" aria-label="YouTube">
                                <i class="fab fa-youtube text-xl"></i>
                            </a>
                            @endif
                            
                            @if($themeSettings['show_linkedin'] ?? false)
                            <a href="{{ $themeSettings['linkedin_url'] ?? '#' }}" target="_blank" class="text-white/80 hover:text-white" aria-label="LinkedIn">
                                <i class="fab fa-linkedin-in text-xl"></i>
                            </a>
                            @endif
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