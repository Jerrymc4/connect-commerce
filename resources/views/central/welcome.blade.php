<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
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
        p {
            line-height: 1.7;
            letter-spacing: 0.01em;
        }
        .text-blue-300 {
            color: #93c5fd;
        }
        .text-baby-blue {
            color: #60a5fa;
        }
    </style>
</head>
<body class="antialiased text-gray-800">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="/" class="text-4xl font-bold"><span class="text-blue-600">Connect</span><span class="text-baby-blue">Commerce</span></a>
            <div class="hidden md:flex space-x-8">
                <a href="#" class="text-lg font-medium text-gray-700 hover:text-blue-600 transition">Home</a>
                <a href="#features" class="text-lg font-medium text-gray-700 hover:text-blue-600 transition">Features</a>
                <a href="#pricing" class="text-lg font-medium text-gray-700 hover:text-blue-600 transition">Pricing</a>
                <a href="#" class="text-lg font-medium text-gray-700 hover:text-blue-600 transition">Contact</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('store.finder') }}" class="text-lg font-medium text-gray-700 hover:text-blue-600 transition">Find Your Store</a>
                <a href="{{ route('store.finder') }}" class="text-lg font-medium text-gray-700 hover:text-blue-600 transition">Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-lg font-medium hover:bg-blue-700 transition">Sign Up</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-pattern py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-6xl font-bold leading-tight mb-8 text-gray-900">Launch Your E-Commerce Store in Minutes</h1>
                    <p class="text-2xl font-medium text-gray-700 mb-10 max-w-xl">Create, customize, and grow your online store with our all-in-one e-commerce platform. No coding required.</p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition text-center">Create Your Store</a>
                        <a href="#how-it-works" class="bg-white text-blue-600 border border-blue-600 px-6 py-3 rounded-lg text-lg font-semibold hover:bg-gray-50 transition text-center">See How It Works</a>
                    </div>
                </div>
                <div class="md:w-1/2 relative">
                    <div class="bg-white rounded-lg shadow-xl overflow-hidden border border-gray-200 p-4">
                        <!-- Abstract dashboard UI representation -->
                        <div class="mb-4 flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-blue-600 mr-3 flex items-center justify-center text-white">
                                <i class="fas fa-store text-sm"></i>
                            </div>
                            <div class="text-xl font-semibold text-gray-800">Store Dashboard</div>
                            <div class="ml-auto flex space-x-2">
                                <div class="w-6 h-6 rounded-full bg-gray-200"></div>
                                <div class="w-6 h-6 rounded-full bg-gray-200"></div>
                            </div>
                        </div>
                        
                        <!-- Dashboard stats -->
                        <div class="grid grid-cols-3 gap-3 mb-4">
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <div class="text-sm font-semibold text-gray-600 mb-1">Revenue</div>
                                <div class="text-lg font-bold text-gray-800">$12,400</div>
                                <div class="text-sm text-green-600 flex items-center mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 12%
                                </div>
                            </div>
                            <div class="bg-green-50 p-3 rounded-lg">
                                <div class="text-sm font-semibold text-gray-600 mb-1">Orders</div>
                                <div class="text-lg font-bold text-gray-800">156</div>
                                <div class="text-sm text-green-600 flex items-center mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 8%
                                </div>
                            </div>
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <div class="text-sm font-semibold text-gray-600 mb-1">Visitors</div>
                                <div class="text-lg font-bold text-gray-800">2,340</div>
                                <div class="text-sm text-green-600 flex items-center mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 24%
                                </div>
                            </div>
                        </div>
                        
                        <!-- Chart representation -->
                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                            <div class="mb-2 text-base font-medium text-gray-700">Performance</div>
                            <div class="flex items-end h-24 space-x-1">
                                <div class="bg-blue-500 w-full rounded-t-sm" style="height: 30%"></div>
                                <div class="bg-blue-500 w-full rounded-t-sm" style="height: 45%"></div>
                                <div class="bg-blue-500 w-full rounded-t-sm" style="height: 25%"></div>
                                <div class="bg-blue-500 w-full rounded-t-sm" style="height: 60%"></div>
                                <div class="bg-blue-500 w-full rounded-t-sm" style="height: 40%"></div>
                                <div class="bg-blue-500 w-full rounded-t-sm" style="height: 70%"></div>
                                <div class="bg-blue-500 w-full rounded-t-sm" style="height: 55%"></div>
                            </div>
                        </div>
                        
                        <!-- Recent orders -->
                        <div class="mb-1">
                            <div class="text-base font-medium text-gray-700 mb-2">Recent Orders</div>
                            <div class="bg-gray-50 p-2 rounded mb-1 flex justify-between items-center">
                                <div class="text-sm font-medium text-gray-800">Order #1082</div>
                                <div class="text-sm font-medium text-green-600">$85.00</div>
                            </div>
                            <div class="bg-gray-50 p-2 rounded mb-1 flex justify-between items-center">
                                <div class="text-sm font-medium text-gray-800">Order #1081</div>
                                <div class="text-sm font-medium text-green-600">$124.00</div>
                            </div>
                            <div class="bg-gray-50 p-2 rounded flex justify-between items-center">
                                <div class="text-sm font-medium text-gray-800">Order #1080</div>
                                <div class="text-sm font-medium text-green-600">$65.00</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Abstract floating panel elements -->
                    <div class="absolute -bottom-4 -left-4 bg-white w-40 p-3 rounded-lg shadow-lg border border-gray-200 z-10 hidden md:block">
                        <div class="text-sm font-semibold text-gray-600 mb-1">Today's Revenue</div>
                        <div class="text-lg font-bold text-gray-800">$1,245</div>
                        <div class="mt-2 flex items-center justify-between">
                            <div class="bg-gray-100 w-full h-1 rounded-full overflow-hidden">
                                <div class="bg-green-500 h-full rounded-full" style="width: 70%"></div>
                            </div>
                            <div class="text-sm font-medium text-green-600 ml-2">+12%</div>
                        </div>
                    </div>
                    
                    <div class="absolute -top-4 -right-4 bg-white w-36 p-3 rounded-lg shadow-lg border border-gray-200 z-10 hidden md:block">
                        <div class="flex justify-between items-center mb-3">
                            <div class="w-2 h-2 rounded-full bg-red-500"></div>
                            <div class="text-sm font-semibold text-gray-600">Live Orders</div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-200 mr-2 flex items-center justify-center text-gray-500 text-sm font-bold">
                                3
                            </div>
                            <div class="text-sm">
                                <div class="font-medium text-gray-800">New Orders</div>
                                <div class="text-gray-600">Last 10 min</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 text-gray-900">Everything You Need to Succeed</h2>
                <p class="text-2xl text-gray-700 max-w-2xl mx-auto">Our platform provides all the tools and features to build, manage, and grow your online store.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-xl shadow-sm text-center">
                    <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full">
                        <i class="fas fa-paint-brush text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4 text-gray-900">Customizable Branding</h3>
                    <p class="text-lg text-gray-700">Personalize your store with custom colors, logos, and themes to match your brand identity.</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl shadow-sm text-center">
                    <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4 text-gray-900">Sales Analytics</h3>
                    <p class="text-lg text-gray-700">Track your store's performance with comprehensive analytics and reports to optimize your business.</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl shadow-sm text-center">
                    <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full">
                        <i class="fas fa-credit-card text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4 text-gray-900">Secure Payments</h3>
                    <p class="text-lg text-gray-700">Accept payments from customers worldwide with our secure and flexible payment processing.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 text-gray-900">How It Works</h2>
                <p class="text-2xl text-gray-700 max-w-2xl mx-auto">Get your online store up and running in just a few simple steps.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-sm text-center">
                    <div class="w-12 h-12 mx-auto mb-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold text-xl">1</div>
                    <h3 class="text-2xl font-semibold mb-4 text-gray-900">Sign Up</h3>
                    <p class="text-lg text-gray-700">Create your account and choose a plan that fits your business needs.</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-sm text-center">
                    <div class="w-12 h-12 mx-auto mb-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold text-xl">2</div>
                    <h3 class="text-2xl font-semibold mb-4 text-gray-900">Customize Your Store</h3>
                    <p class="text-lg text-gray-700">Add your products, set up your branding, and customize your store's appearance.</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-sm text-center">
                    <div class="w-12 h-12 mx-auto mb-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold text-xl">3</div>
                    <h3 class="text-2xl font-semibold mb-4 text-gray-900">Configure Settings</h3>
                    <p class="text-lg text-gray-700">Set up payment methods, shipping options, and tax settings for your store.</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-sm text-center">
                    <div class="w-12 h-12 mx-auto mb-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold text-xl">4</div>
                    <h3 class="text-2xl font-semibold mb-4 text-gray-900">Launch & Grow</h3>
                    <p class="text-lg text-gray-700">Publish your store and start selling to customers around the world.</p>
                </div>
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">Get Started Today</a>
            </div>
        </div>
    </section>

    <!-- Store Showcase -->
    <section id="showcase" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 text-gray-900">Store Templates</h2>
                <p class="text-2xl text-gray-700 max-w-2xl mx-auto">Choose from our beautiful templates to kickstart your online store.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gray-50 rounded-xl shadow-sm overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1556742031-c6961e8560b0?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Store Template" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 text-gray-900">Fashion Boutique</h3>
                        <p class="text-lg text-gray-700 mb-4">Perfect for clothing and accessories stores with a modern, elegant design.</p>
                        <a href="#" class="text-lg text-blue-600 font-medium hover:text-blue-700 transition">Preview Template →</a>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl shadow-sm overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1472851294608-062f824d29cc?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Store Template" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 text-gray-900">Tech Gadgets</h3>
                        <p class="text-lg text-gray-700 mb-4">Designed for electronics and tech products with a sleek, high-tech look.</p>
                        <a href="#" class="text-lg text-blue-600 font-medium hover:text-blue-700 transition">Preview Template →</a>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl shadow-sm overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1470309864661-68328b2cd0a5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Store Template" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 text-gray-900">Home & Decor</h3>
                        <p class="text-lg text-gray-700 mb-4">Ideal for home goods and decor items with a warm, inviting design.</p>
                        <a href="#" class="text-lg text-blue-600 font-medium hover:text-blue-700 transition">Preview Template →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section id="pricing" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 text-gray-900">Affordable Plans for Every Business</h2>
                <p class="text-2xl text-gray-700 max-w-2xl mx-auto">Choose the perfect plan for your business needs with no hidden fees.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold mb-4 text-gray-900">Starter</h3>
                        <div class="mb-4">
                            <span class="text-4xl font-bold text-gray-900">$19</span>
                            <span class="text-lg text-gray-700">/month</span>
                        </div>
                        <p class="text-lg text-gray-700 mb-6">Perfect for new businesses just getting started.</p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> Up to 100 products
                            </li>
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> 2 user accounts
                            </li>
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> Basic analytics
                            </li>
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> Standard support
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="block text-center bg-blue-600 text-white px-4 py-3 rounded-lg text-lg font-medium hover:bg-blue-700 transition w-full">Get Started</a>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-xl shadow-lg overflow-hidden border-2 border-blue-500 transform scale-105 z-10">
                    <div class="bg-blue-600 text-white text-center py-2">
                        <span class="font-medium text-lg">Most Popular</span>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold mb-4 text-gray-900">Professional</h3>
                        <div class="mb-4">
                            <span class="text-4xl font-bold text-gray-900">$49</span>
                            <span class="text-lg text-gray-700">/month</span>
                        </div>
                        <p class="text-lg text-gray-700 mb-6">Ideal for growing businesses with more products.</p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> Up to 1,000 products
                            </li>
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> 5 user accounts
                            </li>
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> Advanced analytics
                            </li>
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> Priority support
                            </li>
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> Custom domain
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="block text-center bg-blue-600 text-white px-4 py-3 rounded-lg text-lg font-medium hover:bg-blue-700 transition w-full">Get Started</a>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold mb-4 text-gray-900">Enterprise</h3>
                        <div class="mb-4">
                            <span class="text-4xl font-bold text-gray-900">$99</span>
                            <span class="text-lg text-gray-700">/month</span>
                        </div>
                        <p class="text-lg text-gray-700 mb-6">For established businesses with high volume sales.</p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> Unlimited products
                            </li>
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> Unlimited user accounts
                            </li>
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> Premium analytics
                            </li>
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> 24/7 dedicated support
                            </li>
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> Custom branding
                            </li>
                            <li class="flex items-center text-lg text-gray-700">
                                <i class="fas fa-check text-green-500 mr-2"></i> API access
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="block text-center bg-blue-600 text-white px-4 py-3 rounded-lg text-lg font-medium hover:bg-blue-700 transition w-full">Get Started</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-20 bg-blue-600 text-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center justify-between">
                <div class="mb-8 lg:mb-0 lg:w-1/2">
                    <h2 class="text-4xl font-bold mb-4">Stay Updated</h2>
                    <p class="text-xl">Get the latest news, features, and tips for growing your online store.</p>
                </div>
                <div class="lg:w-1/2">
                    <form class="flex flex-col sm:flex-row">
                        <input type="email" placeholder="Your email address" class="px-4 py-3 rounded-lg sm:rounded-r-none mb-4 sm:mb-0 text-gray-900 w-full text-lg">
                        <button type="submit" class="bg-gray-900 text-white px-6 py-3 rounded-lg sm:rounded-l-none text-lg font-medium hover:bg-gray-800 transition">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4"><span class="text-white">Connect</span><span class="text-baby-blue">Commerce</span></h3>
                    <p class="text-gray-400 text-lg">The all-in-one e-commerce platform that helps you create, manage, and grow your online store.</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Platform</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-lg text-gray-400 hover:text-white transition">Features</a></li>
                        <li><a href="#" class="text-lg text-gray-400 hover:text-white transition">Pricing</a></li>
                        <li><a href="#" class="text-lg text-gray-400 hover:text-white transition">Templates</a></li>
                        <li><a href="#" class="text-lg text-gray-400 hover:text-white transition">Integrations</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Resources</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-lg text-gray-400 hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="text-lg text-gray-400 hover:text-white transition">Guides</a></li>
                        <li><a href="#" class="text-lg text-gray-400 hover:text-white transition">Support Center</a></li>
                        <li><a href="#" class="text-lg text-gray-400 hover:text-white transition">API Documentation</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Contact Us</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-lg text-gray-400 hover:text-white transition"><i class="fas fa-envelope mr-2"></i> info@connectcommerce.com</a></li>
                        <li><a href="#" class="text-lg text-gray-400 hover:text-white transition"><i class="fas fa-phone mr-2"></i> +1 (123) 456-7890</a></li>
                        <li><a href="#" class="text-lg text-gray-400 hover:text-white transition"><i class="fas fa-map-marker-alt mr-2"></i> 123 Main St, San Francisco, CA</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-lg text-gray-400">&copy; {{ date('Y') }} ConnectCommerce. All rights reserved.</p>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-linkedin-in fa-lg"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html> 