<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
    x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true',
        sidebarOpen: window.innerWidth >= 768,
        isMobile: window.innerWidth < 768
    }" 
    x-init="
        $watch('darkMode', val => localStorage.setItem('darkMode', val));
        window.addEventListener('resize', () => {
            isMobile = window.innerWidth < 768;
            if (!isMobile && !sidebarOpen) {
                sidebarOpen = true;
            }
        });
    " 
    x-bind:class="{ 'dark': darkMode }"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ConnectCommerce') }} - {{ tenant()->name }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        :root {
            --color-primary: #3b82f6;
            --color-primary-hover: #2563eb;
            --color-secondary: #10b981;
            --color-secondary-hover: #059669;
            --color-accent: #8b5cf6;
            --color-accent-hover: #7c3aed;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;
            --color-info: #3b82f6;
        }

        /* Light mode */
        :root {
            --bg-body: #f3f4f6;
            --bg-card: #ffffff;
            --bg-dropdown: #ffffff;
            --bg-sidebar: #111827;
            --bg-input: #ffffff;
            --bg-button: var(--color-primary);
            --text-primary: #111827;
            --text-secondary: #4b5563;
            --text-muted: #6b7280;
            --text-sidebar: #f9fafb;
            --text-sidebar-muted: #9ca3af;
            --border-color: #e5e7eb;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        /* Dark mode */
        .dark {
            --bg-body: #0f172a;
            --bg-card: #1e293b;
            --bg-dropdown: #1e293b;
            --bg-sidebar: #0f172a;
            --bg-input: #1e293b;
            --bg-button: var(--color-primary);
            --text-primary: #f9fafb;
            --text-secondary: #e5e7eb;
            --text-muted: #9ca3af;
            --text-sidebar: #f9fafb;
            --text-sidebar-muted: #9ca3af;
            --border-color: #334155;
            --shadow-color: rgba(0, 0, 0, 0.3);
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-primary);
            transition: background-color 0.3s, color 0.3s;
        }

        .card, .bg-white {
            background-color: var(--bg-card);
            border-color: var(--border-color);
        }

        input, select, textarea {
            background-color: var(--bg-input);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .text-gray-500 {
            color: var(--text-secondary);
        }

        .text-gray-600 {
            color: var(--text-muted);
        }

        .theme-toggle {
            background: none;
            border: none;
            cursor: pointer;
            margin-right: 1rem;
            padding: 0.5rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Sidebar Styles */
        .sidebar {
            background-color: var(--bg-sidebar);
            color: var(--text-sidebar);
            transition: all 0.3s ease;
            width: 240px;
            z-index: 40;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar .logo-section {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
            height: 64px;
        }
        
        .sidebar .logo-section button {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }
        
        .sidebar .logo-section button:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
            color: var(--text-sidebar-muted);
            transition: all 0.2s;
        }
        
        .sidebar-nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-sidebar);
        }
        
        .sidebar-nav-item.active {
            background-color: rgba(59, 130, 246, 0.2);
            color: var(--color-primary);
        }
        
        .sidebar-nav-item i {
            width: 20px;
            text-align: center;
        }
        
        .sidebar-section {
            margin-bottom: 1.5rem;
        }
        
        .sidebar-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-sidebar-muted);
            padding: 0 1rem;
            margin-bottom: 0.5rem;
        }
        
        /* User dropdown positioning fix */
        .user-dropdown {
            right: 0;
            left: auto;
        }
        
        /* Rotate animation for toggle icon */
        .rotate-180 {
            transform: rotate(180deg);
        }
        
        /* Mobile sidebar toggle */
        @media (max-width: 767px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                transform: translateX(-100%);
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
        }
        
        /* Desktop sidebar toggle */
        @media (min-width: 768px) {
            .sidebar {
                transition: width 0.3s ease, opacity 0.2s ease;
            }
            
            .sidebar.closed {
                width: 0;
                opacity: 0;
                visibility: hidden;
            }
            
            .main-content {
                transition: margin-left 0.3s ease;
            }
            
            .main-content.sidebar-open {
                margin-left: 120px;
            }
            
            .main-content.sidebar-closed {
                margin-left: 0;
            }
        }
        
        /* Overlay for mobile when sidebar is open */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 30;
            display: none;
        }
        
        .sidebar-overlay.show {
            display: block;
        }
        
        /* Card enhanced component */
        .card-enhanced {
            background-color: var(--bg-card);
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px var(--shadow-color), 0 2px 4px -1px var(--shadow-color);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        /* Badge Component */
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            border-radius: 9999px;
        }
        
        .badge-success {
            background-color: rgba(16, 185, 129, 0.2);
            color: rgb(16, 185, 129);
        }
        
        .badge-primary {
            background-color: rgba(59, 130, 246, 0.2);
            color: rgb(59, 130, 246);
        }
        
        .badge-danger {
            background-color: rgba(239, 68, 68, 0.2);
            color: rgb(239, 68, 68);
        }
        
        .badge-warning {
            background-color: rgba(245, 158, 11, 0.2);
            color: rgb(245, 158, 11);
        }
        
        /* Enhanced form controls */
        .form-control {
            display: block;
            width: 100%;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: var(--text-primary);
            background-color: var(--bg-input);
            background-clip: padding-box;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-control:focus {
            border-color: var(--color-primary);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }
        
        /* Button Styles */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.5;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            border: 1px solid transparent;
            border-radius: 0.375rem;
            background-color: var(--color-primary);
            color: white;
            transition: all 0.15s ease-in-out;
        }
        
        .btn-primary:hover {
            background-color: var(--color-primary-hover);
        }
        
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.5;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            background-color: var(--bg-card);
            color: var(--text-primary);
            transition: all 0.15s ease-in-out;
        }
        
        .btn-secondary:hover {
            background-color: var(--bg-body);
            border-color: var(--text-muted);
        }

        /* Table Styles */
        .table-enhanced {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table-enhanced th {
            padding: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            border-bottom: 2px solid var(--border-color);
            background-color: var(--bg-body);
        }
        
        .table-enhanced td {
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }
        
        .table-enhanced tr:last-child td {
            border-bottom: none;
        }
        
        .table-enhanced tr:hover {
            background-color: var(--bg-body);
        }
        
        /* Status/colored text classes */
        .text-primary {
            color: var(--text-primary);
        }
        
        .text-secondary {
            color: var(--text-secondary);
        }
        
        .text-muted {
            color: var(--text-muted);
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar Overlay (Mobile only) -->
        <div class="sidebar-overlay" :class="{'show': sidebarOpen && isMobile}" @click="sidebarOpen = false"></div>
        
        <!-- Sidebar -->
        <aside 
            class="sidebar" 
            :class="{
                'open': sidebarOpen && isMobile,
                'closed': !sidebarOpen && !isMobile
            }"
        >
            <div class="logo-section">
                <a href="{{ route('admin.dashboard', [], false) }}" class="flex items-center">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="h-8 w-auto">
                    <span class="ml-2 text-xl font-bold text-white">{{ tenant()->name }}</span>
                </a>
                <button @click="sidebarOpen = false" aria-label="Close Sidebar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4">
                <nav class="space-y-6">
                    <div class="sidebar-section">
                        <h3 class="sidebar-section-title">Dashboard</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('admin.dashboard', [], false) }}" class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-home mr-3"></i>
                                Dashboard
                            </a>
                        </div>
                    </div>
                    <div class="sidebar-section">
                        <h3 class="sidebar-section-title">Catalog</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('admin.products', [], false) }}" class="sidebar-nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                                <i class="fas fa-box mr-3"></i>
                                Products
                            </a>
                        </div>
                    </div>
                    <div class="sidebar-section">
                        <h3 class="sidebar-section-title">Sales</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('admin.orders', [], false) }}" class="sidebar-nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                                <i class="fas fa-shopping-cart mr-3"></i>
                                Orders
                            </a>
                            <a href="{{ route('admin.customers', [], false) }}" class="sidebar-nav-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                                <i class="fas fa-users mr-3"></i>
                                Customers
                            </a>
                        </div>
                    </div>
                    <div class="sidebar-section">
                        <h3 class="sidebar-section-title">Configuration</h3>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('admin.settings', [], false) }}" class="sidebar-nav-item {{ request()->routeIs('admin.settings*') || request()->routeIs('admin.themes*') || request()->routeIs('admin.discounts*') ? 'active' : '' }}">
                                <i class="fas fa-cog mr-3"></i>
                                Settings
                            </a>
                            <a href="{{ route('admin.audit-logs.index', [], false) }}" class="sidebar-nav-item {{ request()->routeIs('admin.audit-logs*') ? 'active' : '' }}">
                                <i class="fas fa-history mr-3"></i>
                                Audit Logs
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div 
            class="flex-1 flex flex-col min-h-screen main-content"
            :class="{
                'sidebar-open': sidebarOpen && !isMobile,
                'sidebar-closed': !sidebarOpen && !isMobile
            }"
        >
            <!-- Top Bar -->
            <header class="z-20 bg-card border-b border-border-color h-16 sticky top-0">
                <div class="h-full px-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button 
                            @click="sidebarOpen = !sidebarOpen" 
                            class="p-2 rounded-md focus:outline-none text-primary hover:bg-opacity-10 hover:bg-primary transition-all duration-200"
                            x-show="!sidebarOpen || isMobile"
                        >
                            <i class="fas fa-bars"></i>
                        </button>
                        <span class="ml-4 text-lg font-semibold text-primary hidden md:block">{{ tenant()->name }}</span>
                    </div>
                    <div class="flex items-center">
                        <!-- Theme Toggle -->
                        <button class="theme-toggle" @click="darkMode = !darkMode">
                            <template x-if="darkMode">
                                <i class="fas fa-sun text-yellow-400 text-xl"></i>
                            </template>
                            <template x-if="!darkMode">
                                <i class="fas fa-moon text-gray-600 text-xl"></i>
                            </template>
                        </button>
                        
                        <!-- User Menu -->
                        <div x-data="{ userMenuOpen: false }" class="relative">
                            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center text-sm rounded-full focus:outline-none p-1 border-2 border-transparent hover:border-primary">
                                <span class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-user"></i>
                                </span>
                            </button>
                            <div x-show="userMenuOpen" @click.away="userMenuOpen = false" class="user-dropdown origin-top-right absolute mt-2 w-48 rounded-md shadow-lg py-1 bg-dropdown ring-1 ring-black ring-opacity-5 z-50" style="display: none;">
                                <a href="#" class="block px-4 py-2 text-sm text-primary hover:bg-opacity-10 hover:bg-primary">Your Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-primary hover:bg-opacity-10 hover:bg-primary">Settings</a>
                                <div class="border-t border-border-color my-1"></div>
                                <form method="POST" action="{{ route('tenant.logout', [], false) }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm hover:bg-opacity-10 hover:bg-danger text-danger">Sign out</button>
                                </form>
                            </div>
                        </div>
                        <!-- Add this to the header navigation area, near user profile or other main navigation -->
                        <div class="ml-3 relative">
                            <a href="{{ route('store.preview') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors" target="_blank">
                                <i class="fas fa-external-link-alt mr-1"></i> Preview Store
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto p-4">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-card border-t border-border-color p-4 text-center text-muted text-sm">
                <p>&copy; {{ date('Y') }} {{ tenant()->name }} - Powered by ConnectCommerce</p>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html> 