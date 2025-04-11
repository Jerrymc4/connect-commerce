<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            font-size: 1.05rem;
        }
        .sidebar-link {
            @apply flex items-center py-2 px-4 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all;
        }
        .sidebar-link.active {
            @apply bg-blue-50 text-blue-700 font-medium;
        }
        .sidebar-icon {
            @apply w-5 h-5 mr-3;
        }
    </style>
    @stack('styles')
</head>
<body class="antialiased text-gray-800 bg-gray-50 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm py-4 z-10">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="/" class="text-4xl font-bold"><span class="text-blue-600">Connect</span><span class="text-blue-300">Commerce</span></a>
            <div class="flex items-center space-x-6">
                <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-blue-600 transition">
                    <i class="fas fa-user-circle mr-1"></i> Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-blue-600 transition">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content with Sidebar -->
    <div class="flex flex-1 container mx-auto px-4 py-6">
        <!-- Sidebar -->
        <div class="w-64 mr-8">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="mb-6">
                    <div class="font-medium text-sm uppercase text-gray-500 tracking-wider mb-3">Main</div>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt sidebar-icon"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.stores') }}" class="sidebar-link {{ request()->routeIs('admin.stores*') ? 'active' : '' }}">
                        <i class="fas fa-store sidebar-icon"></i> Stores
                    </a>
                </div>
                
                <div class="mb-6">
                    <div class="font-medium text-sm uppercase text-gray-500 tracking-wider mb-3">Store Management</div>
                    <a href="{{ route('admin.products') }}" class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                        <i class="fas fa-box sidebar-icon"></i> Products
                    </a>
                    <a href="{{ route('admin.orders') }}" class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart sidebar-icon"></i> Orders
                    </a>
                    <a href="{{ route('admin.customers') }}" class="sidebar-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                        <i class="fas fa-users sidebar-icon"></i> Customers
                    </a>
                </div>
                
                <div>
                    <div class="font-medium text-sm uppercase text-gray-500 tracking-wider mb-3">Settings</div>
                    <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog sidebar-icon"></i> Store Settings
                    </a>
                    <a href="{{ route('admin.theme') }}" class="sidebar-link {{ request()->routeIs('admin.theme*') ? 'active' : '' }}">
                        <i class="fas fa-paint-brush sidebar-icon"></i> Theme
                    </a>
                    <a href="{{ route('admin.payments') }}" class="sidebar-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                        <i class="fas fa-credit-card sidebar-icon"></i> Payments
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="flex-1">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html> 