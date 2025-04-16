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
        <header class="bg-[var(--background)] shadow-sm">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('storefront.home') }}" class="font-bold text-xl text-[var(--text-primary)]">
                            @if(!empty($themeSettings['logo_url']))
                                <img src="{{ Storage::url($themeSettings['logo_url']) }}" alt="{{ $storeName ?? tenant()->name ?? config('app.name') }}" class="h-10 w-auto">
                            @else
                                {{ $storeName ?? tenant()->name ?? config('app.name') }}
                            @endif
                        </a>
                    </div>
                    
                    <!-- Main Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('storefront.home') }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-colors">Home</a>
                        <a href="{{ route('storefront.products.index') }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-colors">Products</a>
                        {{-- <a href="{{ route('storefront.categories.index') }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-colors">Categories</a> --}}
                        <a href="#" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-colors">About</a>
                        <a href="#" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-colors">Contact</a>
                    </div>
                    
                    <!-- Right side icons -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <button type="button" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)]">
                            <i class="fas fa-search"></i>
                        </button>
                        
                        <!-- Cart icon -->
                        <a href="{{ route('storefront.cart') }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] relative">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            @if(class_exists('Cart') && Cart::instance('default')->count() > 0)
                            <span class="absolute -top-2 -right-2 bg-[var(--primary)] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ Cart::instance('default')->count() }}
                            </span>
                            @endif
                        </a>
                        
                        <!-- User Authentication -->
                        @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-[var(--text-secondary)] hover:text-[var(--text-primary)] focus:outline-none">
                                <i class="fas fa-user-circle text-xl mr-1"></i>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <!-- Dropdown menu -->
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-[var(--background)] rounded-md shadow-md py-1 z-50">
                                <a href="{{ route('customer.account') }}" class="block px-4 py-2 text-sm text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-gray-100">My Account</a>
                                <a href="{{ route('customer.orders') }}" class="block px-4 py-2 text-sm text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-gray-100">My Orders</a>
                                <div class="border-t border-gray-200 my-1"></div>
                                <form action="{{ route('customer.logout') }}" method="POST" class="block w-full">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('customer.login') }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-sm">Login</a>
                        <span class="text-[var(--text-secondary)]">|</span>
                        <a href="{{ route('customer.register') }}" class="text-[var(--text-secondary)] hover:text-[var(--text-primary)] text-sm">Register</a>
                        @endauth
                        
                        <!-- Mobile menu button -->
                        <button type="button" class="md:hidden text-[var(--text-secondary)] hover:text-[var(--text-primary)]">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>
        @else
        <!-- Minimal header for login/register pages -->
        <div class="py-6 bg-[var(--background)] shadow-sm">
            <div class="container mx-auto px-4">
                <a href="{{ route('storefront.home') }}" class="font-bold text-xl text-[var(--text-primary)]">
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