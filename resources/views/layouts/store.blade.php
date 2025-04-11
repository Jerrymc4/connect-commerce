<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ConnectCommerce') }} - {{ isset($storeName) ? $storeName : 'Store Dashboard' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts -->
    {!! tenant_vite_assets(['resources/css/app.css', 'resources/js/app.js']) !!}
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-white border-r border-gray-200">
                <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
                    <!-- Store Logo/Name -->
                    <div class="flex items-center flex-shrink-0 px-4 mb-5">
                        <a href="{{ route('store.dashboard') }}" class="flex items-center">
                            <div class="w-10 h-10 bg-blue-600 rounded-md flex items-center justify-center text-white font-bold mr-2">
                                <i class="fas fa-store"></i>
                            </div>
                            <span class="text-xl font-semibold text-gray-800 truncate">{{ tenant()->name }}</span>
                        </a>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="flex-1 px-2 space-y-1">
                        <a href="{{ route('store.dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('store.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-tachometer-alt mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('store.dashboard') ? 'text-blue-500' : 'text-gray-500 group-hover:text-gray-500' }}"></i>
                            Dashboard
                        </a>
                        
                        <a href="{{ route('store.products', [], false) }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('store.products*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-box mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('store.products*') ? 'text-blue-500' : 'text-gray-500 group-hover:text-gray-500' }}"></i>
                            Products
                        </a>
                        
                        <a href="{{ route('store.orders', [], false) }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('store.orders*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-shopping-cart mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('store.orders*') ? 'text-blue-500' : 'text-gray-500 group-hover:text-gray-500' }}"></i>
                            Orders
                        </a>
                        
                        <a href="{{ route('store.customers', [], false) }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('store.customers*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-users mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('store.customers*') ? 'text-blue-500' : 'text-gray-500 group-hover:text-gray-500' }}"></i>
                            Customers
                        </a>
                        
                        <a href="{{ route('store.themes', [], false) }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('store.themes*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-paint-brush mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('store.themes*') ? 'text-blue-500' : 'text-gray-500 group-hover:text-gray-500' }}"></i>
                            Theme
                        </a>
                        
                        <a href="{{ route('store.settings', [], false) }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('store.settings*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-cog mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('store.settings*') ? 'text-blue-500' : 'text-gray-500 group-hover:text-gray-500' }}"></i>
                            Settings
                        </a>
                    </nav>
                </div>
                
                <!-- User Profile -->
                <div class="flex-shrink-0 p-4 border-t border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <span class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                            <a href="{{ route('tenant.logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                               class="text-xs font-medium text-gray-500 hover:text-gray-700">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('tenant.logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <!-- Top Navigation Bar -->
            <div class="relative z-10 flex-shrink-0 flex h-16 bg-white border-b border-gray-200">
                <button type="button" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 md:hidden" aria-expanded="false">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars h-6 w-6"></i>
                </button>
                
                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex">
                        <form class="w-full flex md:ml-0" action="#" method="GET">
                            <label for="search-field" class="sr-only">Search</label>
                            <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                    <i class="fas fa-search h-5 w-5"></i>
                                </div>
                                <input id="search-field" class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent sm:text-sm" placeholder="Search" type="search" name="search">
                            </div>
                        </form>
                    </div>
                    
                    <div class="ml-4 flex items-center md:ml-6">
                        <!-- Notification button -->
                        <button type="button" class="p-1 rounded-full text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span class="sr-only">View notifications</span>
                            <i class="fas fa-bell h-6 w-6"></i>
                        </button>
                        
                        <!-- Visit Store button -->
                        <a href="/" target="_blank" class="ml-3 bg-blue-100 p-1 rounded-full text-blue-600 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span class="sr-only">Visit store</span>
                            <i class="fas fa-external-link-alt h-6 w-6"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Main Content Area -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                <div class="py-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html> 