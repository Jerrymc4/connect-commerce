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
    
    <!-- Theme CSS File - Inline style approach -->
    <style>
        :root {
            --primary: {{ $themeSettings['primary_color'] ?? '#3B82F6' }};
            --secondary: {{ $themeSettings['button_bg_color'] ?? '#10B981' }};
            --background: {{ $themeSettings['body_bg_color'] ?? '#ffffff' }};
            --text-primary: {{ $themeSettings['navbar_text_color'] ?? '#111827' }};
            --text-secondary: {{ $themeSettings['navbar_text_color'] ?? '#4B5563' }};
            --header-bg: {{ $themeSettings['body_bg_color'] ?? '#ffffff' }};
            --footer-bg: {{ $themeSettings['footer_bg_color'] ?? '#1F2937' }};
            --heading-font: {{ $themeSettings['font_family'] ?? 'Inter' }};
            --body-font: {{ $themeSettings['font_family'] ?? 'Inter' }};
            --base-font-size: 16px;
            --heading-weight: 600;
        }
        
        body {
            font-family: var(--body-font);
            font-size: var(--base-font-size);
            color: var(--text-primary);
            background-color: var(--background);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--heading-font);
            font-weight: var(--heading-weight);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #FFFFFF;
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            border-color: var(--secondary);
            color: #FFFFFF;
        }
        
        header {
            background-color: var(--header-bg);
        }
        
        footer {
            background-color: var(--footer-bg);
        }
        
        /* TailwindCSS Custom Colors */
        .bg-primary { background-color: var(--primary) !important; }
        .bg-secondary { background-color: var(--secondary) !important; }
        .bg-background { background-color: var(--background) !important; }
        .text-primary { color: var(--text-primary) !important; }
        .text-secondary { color: var(--text-secondary) !important; }
        .from-primary { --tw-gradient-from: var(--primary) !important; }
        .to-secondary { --tw-gradient-to: var(--secondary) !important; }
        
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased h-full bg-body text-primary">
    <div class="min-h-full flex flex-col">
        <!-- Header - Only show on non-login/register pages -->
        @if(!Route::is('customer.login') && !Route::is('customer.register') && !Route::is('customer.password.request'))
        <header class="bg-card shadow-color">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('storefront.home') }}" class="font-bold text-xl text-primary">
                            @if(!empty($themeSettings['logo_url']))
                                <img src="{{ Storage::url($themeSettings['logo_url']) }}" alt="{{ $storeName ?? tenant()->name ?? config('app.name') }}" class="h-10 w-auto">
                            @else
                                {{ $storeName ?? tenant()->name ?? config('app.name') }}
                            @endif
                        </a>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="hidden md:flex space-x-8">
                        <a href="{{ route('storefront.home') }}" class="text-secondary hover:text-primary">Home</a>
                        <a href="{{ route('storefront.products.index') }}" class="text-secondary hover:text-primary">Products</a>
                        <a href="#" class="text-secondary hover:text-primary">Categories</a>
                        <a href="#" class="text-secondary hover:text-primary">About</a>
                        <a href="#" class="text-secondary hover:text-primary">Contact</a>
                    </nav>
                    
                    <!-- Right side icons -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <button type="button" class="text-secondary hover:text-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        
                        <!-- Cart icon (always visible) -->
                        <a href="{{ route('storefront.cart') }}" class="text-primary hover:text-primary-dark relative">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span class="absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                        </a>
                        
                        <!-- Profile section - conditional display -->
                        @auth('customer')
                            <!-- User is logged in - show profile dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-primary hover:text-primary-dark focus:outline-none">
                                    <span class="mr-1">{{ Auth::guard('customer')->user()->name }}</span>
                                    <i class="fas fa-user-circle text-xl"></i>
                                    <i class="fas fa-chevron-down text-xs ml-1"></i>
                                </button>
                                
                                <!-- Dropdown menu -->
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-card rounded-md shadow-color py-1 z-50">
                                    <a href="{{ route('customer.account') }}" class="block px-4 py-2 text-sm text-primary hover:bg-body">My Account</a>
                                    <a href="{{ route('customer.orders') }}" class="block px-4 py-2 text-sm text-primary hover:bg-body">My Orders</a>
                                    <a href="{{ route('customer.wishlist') }}" class="block px-4 py-2 text-sm text-primary hover:bg-body">Wishlist</a>
                                    <div class="border-t border-border-color"></div>
                                    <form method="POST" action="/customer/logout">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-primary hover:bg-body">
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- User is not logged in - show login/register links -->
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('customer.login') }}" class="text-primary hover:text-primary-dark">
                                    <span>Sign In</span>
                                </a>
                                <span class="text-muted">|</span>
                                <a href="{{ route('customer.register') }}" class="text-primary hover:text-primary-dark">
                                    <span>Register</span>
                                </a>
                            </div>
                        @endauth
                        
                        <!-- Mobile menu button -->
                        <button type="button" class="md:hidden text-secondary hover:text-primary">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>
        @else
        <!-- Minimal header for login/register pages -->
        <div class="py-6 bg-card shadow-color">
            <div class="container mx-auto px-4">
                <a href="{{ route('storefront.home') }}" class="font-bold text-xl text-primary">
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
            @if(!empty($themeSettings['banner_image']) || !empty($themeSettings['banner_title']) || !empty($themeSettings['banner_subtitle']))
            <div class="bg-gradient-to-r from-primary to-secondary text-{{ $themeSettings['banner_text_color'] ?? 'white' }} 
                {{ $themeSettings['banner_height'] ?? 'medium' === 'small' ? 'py-8' : ($themeSettings['banner_height'] ?? 'medium' === 'medium' ? 'py-12' : ($themeSettings['banner_height'] ?? 'medium' === 'large' ? 'py-16' : 'min-h-screen py-24')) }}
                {{ $themeSettings['banner_layout'] ?? 'left-aligned' === 'overlay' ? 'relative' : '' }}">
                
                @if($themeSettings['banner_layout'] ?? 'left-aligned' === 'overlay' && !empty($themeSettings['banner_image']))
                <div class="absolute inset-0 w-full h-full overflow-hidden">
                    <img src="{{ Storage::url($themeSettings['banner_image']) }}" alt="Banner" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                </div>
                @endif
                
                <div class="container mx-auto px-4 relative">
                    @if($themeSettings['banner_layout'] ?? 'left-aligned' === 'center')
                    <div class="text-center max-w-4xl mx-auto">
                        @if(!empty($themeSettings['banner_title']))
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">{{ $themeSettings['banner_title'] }}</h1>
                        @endif
                        
                        @if(!empty($themeSettings['banner_subtitle']))
                        <p class="text-lg md:text-xl mb-6 opacity-90">{{ $themeSettings['banner_subtitle'] }}</p>
                        @endif
                        
                        @if(!empty($themeSettings['banner_cta_text']))
                        <a href="{{ $themeSettings['banner_cta_url'] ?? route('storefront.products.index') }}" 
                           class="inline-block px-6 py-3 rounded-lg font-semibold transition-colors"
                           style="background-color: {{ $themeSettings['banner_cta_bg_color'] ?? '#FFFFFF' }}; color: {{ $themeSettings['banner_cta_text_color'] ?? '#4F46E5' }};">
                            {{ $themeSettings['banner_cta_text'] }}
                        </a>
                        @endif
                        
                        @if(!empty($themeSettings['banner_image']) && $themeSettings['banner_layout'] ?? 'left-aligned' === 'center')
                        <div class="mt-8">
                            <img src="{{ Storage::url($themeSettings['banner_image']) }}" alt="Banner" class="rounded-lg shadow-color w-full max-w-2xl mx-auto">
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="flex flex-col {{ $themeSettings['banner_layout'] ?? 'left-aligned' === 'right-aligned' ? 'md:flex-row-reverse' : 'md:flex-row' }} items-center justify-between">
                        <div class="mb-6 md:mb-0 md:w-1/2">
                            @if(!empty($themeSettings['banner_title']))
                            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">{{ $themeSettings['banner_title'] }}</h1>
                            @endif
                            
                            @if(!empty($themeSettings['banner_subtitle']))
                            <p class="text-lg md:text-xl mb-6 opacity-90">{{ $themeSettings['banner_subtitle'] }}</p>
                            @endif
                            
                            @if(!empty($themeSettings['banner_cta_text']))
                            <a href="{{ $themeSettings['banner_cta_url'] ?? route('storefront.products.index') }}" 
                               class="inline-block px-6 py-3 rounded-lg font-semibold transition-colors"
                               style="background-color: {{ $themeSettings['banner_cta_bg_color'] ?? '#FFFFFF' }}; color: {{ $themeSettings['banner_cta_text_color'] ?? '#4F46E5' }};">
                                {{ $themeSettings['banner_cta_text'] }}
                            </a>
                            @endif
                        </div>
                        
                        @if(!empty($themeSettings['banner_image']) && $themeSettings['banner_layout'] ?? 'left-aligned' !== 'overlay')
                        <div class="md:w-1/2">
                            <img src="{{ Storage::url($themeSettings['banner_image']) }}" alt="Banner" class="rounded-lg shadow-color w-full">
                        </div>
                        @endif
                    </div>
                    @endif
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
        <footer class="bg-primary text-white py-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-xl font-semibold mb-4">{{ $storeName ?? tenant()->name ?? config('app.name') }}</h3>
                        <p class="text-white/80 mb-4">Your one-stop shop for quality products at affordable prices.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-white/80 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white/80 hover:text-white"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-white/80 hover:text-white"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white/80 hover:text-white"><i class="fab fa-pinterest"></i></a>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Shop</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('storefront.products.index') }}" class="text-white/80 hover:text-white">All Products</a></li>
                            <li><a href="#" class="text-white/80 hover:text-white">New Arrivals</a></li>
                            <li><a href="#" class="text-white/80 hover:text-white">Featured</a></li>
                            <li><a href="#" class="text-white/80 hover:text-white">Sale</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Customer Service</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-white/80 hover:text-white">Contact Us</a></li>
                            <li><a href="#" class="text-white/80 hover:text-white">Shipping & Returns</a></li>
                            <li><a href="#" class="text-white/80 hover:text-white">FAQ</a></li>
                            <li><a href="#" class="text-white/80 hover:text-white">Size Guide</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact</h3>
                        <ul class="space-y-2 text-white/80">
                            <li><i class="fas fa-map-marker-alt mr-2"></i> 123 Main St, Anytown, USA</li>
                            <li><i class="fas fa-phone mr-2"></i> (123) 456-7890</li>
                            <li><i class="fas fa-envelope mr-2"></i> info@{{ tenant()->domain ?? 'example.com' }}</li>
                        </ul>
                    </div>
                </div>
                
                <hr class="border-white/20 my-8">
                
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-white/80 text-sm">
                        &copy; {{ date('Y') }} {{ $storeName ?? tenant()->name ?? config('app.name') }}. All rights reserved.
                    </p>
                    <div class="mt-4 md:mt-0">
                        <ul class="flex space-x-6 text-sm text-white/80">
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
        <footer class="py-4 bg-card border-t border-border-color">
            <div class="container mx-auto px-4 text-center">
                <p class="text-muted text-sm">
                    &copy; {{ date('Y') }} {{ $storeName ?? tenant()->name ?? config('app.name') }}. All rights reserved.
                </p>
            </div>
        </footer>
        @endif
    </div>
    
    @stack('scripts')
</body>
</html> 