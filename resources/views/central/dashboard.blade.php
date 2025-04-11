<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Central Dashboard</title>
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
        }
    </style>
</head>
<body class="antialiased text-gray-800 bg-gray-50 flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm py-4">
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

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 flex-grow">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Central Dashboard</h1>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Your Stores</h2>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-1"></i> Create New Store
                    </a>
                </div>
                @if(isset($stores) && count($stores) > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($stores as $store)
                            <div class="py-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-800">{{ $store->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $store->domains->first()->domain ?? 'No domain set' }}</p>
                                    </div>
                                    <a href="https://{{ $store->domains->first()->domain ?? '#' }}/dashboard" class="text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-external-link-alt mr-1"></i> Go to Store
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-center">
                        <p class="text-gray-600">You don't have any stores yet.</p>
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium inline-block mt-2">
                            <i class="fas fa-plus mr-1"></i> Create Your First Store
                        </a>
                    </div>
                @endif
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Stats</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <p class="text-sm text-blue-600 font-medium">Total Stores</p>
                        <p class="text-2xl font-bold text-blue-800">{{ isset($stores) ? count($stores) : '0' }}</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                        <p class="text-sm text-green-600 font-medium">Active Stores</p>
                        <p class="text-2xl font-bold text-green-800">{{ isset($activeStores) ? $activeStores : '0' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-white py-8 mt-auto">
        <div class="container mx-auto px-4">
            <div class="text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} ConnectCommerce. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html> 