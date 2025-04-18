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
            @apply flex items-center py-2 px-4 text-gray-200 rounded-lg hover:bg-blue-700 hover:text-white transition-all;
        }
        .sidebar-link.active {
            @apply bg-blue-700 text-white font-medium;
        }
        .sidebar-icon {
            @apply w-5 h-5 mr-3;
        }
    </style>
    @stack('styles')
</head>
<body class="antialiased text-gray-200 bg-gray-100 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-black shadow-md py-4 z-10">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="/" class="text-4xl font-bold"><span class="text-blue-500">Connect</span><span class="text-blue-300">Commerce</span></a>
            <div class="flex items-center space-x-6">
                <a href="{{ route('profile.edit') }}" class="text-gray-300 hover:text-blue-400 transition">
                    <i class="fas fa-user-circle mr-1"></i> Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-300 hover:text-blue-400 transition">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Success message -->
    {{-- @if (session('success'))
        <div id="success-alert" class=" text-white px-4 py-3 rounded shadow-md m-4 flex justify-between items-center">
            <div class="flex items-center">
                {{-- <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg> --}}
                {{-- <span>{{ session('success') }}</span> --}}
            </div>
            {{-- <button type="button" onclick="document.getElementById('success-alert').remove()" class="text-white hover:text-gray-200">
                <span class="text-2xl">&times;</span>
            </button> --}}
        </div>
        {{-- <script>
            setTimeout(function() {
                const element = document.getElementById('success-alert');
                if (element) element.remove();
            }, 5000);
        </script> --}}
    @endif --}}

    <!-- Error message -->
    @if (session('error'))
        <div id="error-alert" class="bg-red-500 text-white px-4 py-3 rounded shadow-md m-4 flex justify-between items-center">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="document.getElementById('error-alert').remove()" class="text-white hover:text-gray-200">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
        <script>
            setTimeout(function() {
                const element = document.getElementById('error-alert');
                if (element) element.remove();
            }, 5000);
        </script>
    @endif

    <!-- Main Content with Sidebar -->
    <div class="flex flex-1 container mx-auto px-4 py-6">
        <!-- Sidebar -->
        <div class="w-64 mr-8">
            <div class="bg-gray-800 rounded-lg shadow-lg p-4">
                <div class="mb-6">
                    <div class="font-medium text-sm uppercase text-gray-400 tracking-wider mb-3">Main</div>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt sidebar-icon"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.stores') }}" class="sidebar-link {{ request()->routeIs('admin.stores*') ? 'active' : '' }}">
                        <i class="fas fa-store sidebar-icon"></i> Stores
                    </a>
                </div>
                
                <div class="mb-6">
                    <div class="font-medium text-sm uppercase text-gray-400 tracking-wider mb-3">Store Management</div>
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
                    <div class="font-medium text-sm uppercase text-gray-400 tracking-wider mb-3">Settings</div>
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