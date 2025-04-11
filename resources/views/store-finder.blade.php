<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Find Your Store</title>
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
        .hero-pattern {
            background-color: #f9fafb;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e5e7eb' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .text-blue-300 {
            color: #93c5fd;
        }
        .text-baby-blue {
            color: #60a5fa;
        }
    </style>
</head>
<body class="antialiased text-gray-800 bg-gray-50 flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="/" class="text-4xl font-bold"><span class="text-blue-600">Connect</span><span class="text-baby-blue">Commerce</span></a>
            <div class="flex items-center space-x-4">
                <a href="{{ route('register') }}" class="text-lg font-medium text-gray-700 hover:text-blue-600 transition">Register</a>
            </div>
        </div>
    </nav>

    <!-- Validation Errors -->
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4 mx-auto max-w-md" role="alert">
        <strong class="font-bold">Oops! Something went wrong.</strong>
        <ul class="mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Store Finder Form -->
    <div class="container mx-auto px-4 py-12 flex-grow">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-8">
                <h2 class="text-3xl font-bold mb-6 text-center text-gray-900">Find Your Store</h2>
                <p class="text-gray-600 mb-8 text-center">Enter your store name below to log in to your ConnectCommerce store.</p>
                
                <form method="POST" action="{{ route('store.find') }}">
                    @csrf
                    
                    <!-- Store Name -->
                    <div class="mb-6">
                        <label for="store_name" class="block text-lg font-medium text-gray-700 mb-2">Store Name</label>
                        <input id="store_name" name="store_name" type="text" value="{{ old('store_name') }}" required autofocus 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 outline-none"
                            placeholder="Your store name">
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="mb-6">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200">
                            Continue to Login
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-gray-600">
                            Don't have a store yet? 
                            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Create one now
                            </a>
                        </p>
                    </div>
                </form>
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