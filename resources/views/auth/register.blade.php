<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Register</title>
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
        .step-inactive {
            color: #9CA3AF;
        }
        .step-active {
            color: #3B82F6;
        }
        .step-completed {
            color: #10B981;
        }
    </style>
</head>
<body class="antialiased text-gray-800 bg-gray-50 flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="/" class="text-4xl font-bold"><span class="text-blue-600">Connect</span><span class="text-baby-blue">Commerce</span></a>
            <div class="flex items-center space-x-4">
                <a href="/login" class="text-lg font-medium text-gray-700 hover:text-blue-600 transition">Login</a>
                <a href="/register" class="text-lg font-medium text-blue-600 transition">Register</a>
            </div>
        </div>
    </nav>

    <!-- Validation Errors -->
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4 mx-auto max-w-4xl" role="alert">
        <strong class="font-bold">Oops! Something went wrong.</strong>
        <ul class="mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Progress Bar -->
    <div class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="w-1/3 text-center">
                    <div id="step1-indicator" class="step-active">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border-2 border-blue-600 mb-2">
                            <i id="step1-icon" class="fas fa-check"></i>
                        </span>
                        <p class="text-lg font-medium">Select Plan</p>
                    </div>
                </div>
                <div class="w-1/3 text-center">
                    <div id="step2-indicator" class="step-inactive">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border-2 border-gray-300 mb-2">
                            <i id="step2-icon" class="fas fa-user"></i>
                        </span>
                        <p class="text-lg font-medium">User Details</p>
                    </div>
                </div>
                <div class="w-1/3 text-center">
                    <div id="step3-indicator" class="step-inactive">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border-2 border-gray-300 mb-2">
                            <i id="step3-icon" class="fas fa-store"></i>
                        </span>
                        <p class="text-lg font-medium">Store Details</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 relative">
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                    <div id="progress-bar" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-600" style="width: 33%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Multi-step Form -->
    <div class="container mx-auto px-4 py-12 flex-grow">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <form id="register-form" method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="current_step" id="current_step" value="1">
                <input type="hidden" name="selected_plan" id="selected_plan" value="{{ old('selected_plan') }}">

                <!-- Step 1: Plan Selection -->
                <div id="step1" class="p-8">
                    <h2 class="text-3xl font-bold mb-8 text-center text-gray-900">Choose Your Plan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                        <div class="bg-gray-50 rounded-xl shadow-sm overflow-hidden border border-gray-200 hover:border-blue-500 cursor-pointer plan-option" data-plan="starter">
                            <div class="p-6">
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
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl shadow-lg overflow-hidden border-2 border-blue-500 transform scale-105 cursor-pointer plan-option" data-plan="professional">
                            <div class="bg-blue-600 text-white text-center py-2">
                                <span class="font-medium text-lg">Most Popular</span>
                            </div>
                            <div class="p-6">
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
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl shadow-sm overflow-hidden border border-gray-200 hover:border-blue-500 cursor-pointer plan-option" data-plan="enterprise">
                            <div class="p-6">
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
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" id="step1-button" class="bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition disabled:opacity-50">Continue</button>
                    </div>
                </div>

                <!-- Step 2: User Details -->
                <div id="step2" class="p-8 hidden">
                    <h2 class="text-3xl font-bold mb-8 text-center text-gray-900">Account Information</h2>
                    
                    <div class="mb-6">
                        <label for="name" class="block text-lg font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <div class="mb-6">
                        <label for="email" class="block text-lg font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="password" class="block text-lg font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" id="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-lg font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <button type="button" id="back-to-step1" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg text-lg font-semibold hover:bg-gray-50 transition">Back</button>
                        <button type="button" id="step2-button" class="bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">Continue</button>
                    </div>
                </div>

                <!-- Step 3: Store Details -->
                <div id="step3" class="p-8 hidden">
                    <h2 class="text-3xl font-bold mb-8 text-center text-gray-900">Store Details</h2>
                    
                    <div class="mb-6">
                        <label for="store_name" class="block text-lg font-medium text-gray-700 mb-2">Store Name</label>
                        <input type="text" id="store_name" name="store_name" value="{{ old('store_name') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <p class="text-sm text-gray-500 mt-1">Your store URL will be automatically generated based on this name.</p>
                    </div>
                    
                    <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    A unique domain will be automatically generated for your store based on your store name (e.g., your-store-12345.connectcommerce.test).
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="business_type" class="block text-lg font-medium text-gray-700 mb-2">Business Type</label>
                        <select id="business_type" name="business_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select a business type</option>
                            <option value="retail" {{ old('business_type') == 'retail' ? 'selected' : '' }}>Retail</option>
                            <option value="digital_products" {{ old('business_type') == 'digital_products' ? 'selected' : '' }}>Digital Products</option>
                            <option value="services" {{ old('business_type') == 'services' ? 'selected' : '' }}>Services</option>
                            <option value="food_beverage" {{ old('business_type') == 'food_beverage' ? 'selected' : '' }}>Food & Beverage</option>
                            <option value="handmade" {{ old('business_type') == 'handmade' ? 'selected' : '' }}>Handmade / Artisan</option>
                            <option value="other" {{ old('business_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-between">
                        <button type="button" id="back-to-step2" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg text-lg font-semibold hover:bg-gray-50 transition">Back</button>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">Create My Store</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white py-8 mt-auto">
        <div class="container mx-auto px-4">
            <div class="text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} <span class="text-blue-600">Connect</span><span class="text-baby-blue">Commerce</span>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const form = document.getElementById('register-form');
            const currentStepInput = document.getElementById('current_step');
            const selectedPlanInput = document.getElementById('selected_plan');
            const progressBar = document.getElementById('progress-bar');
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const step3 = document.getElementById('step3');
            const step1Button = document.getElementById('step1-button');
            const step2Button = document.getElementById('step2-button');
            const backToStep1 = document.getElementById('back-to-step1');
            const backToStep2 = document.getElementById('back-to-step2');
            const step1Indicator = document.getElementById('step1-indicator');
            const step2Indicator = document.getElementById('step2-indicator');
            const step3Indicator = document.getElementById('step3-indicator');
            const step1Icon = document.getElementById('step1-icon');
            const step2Icon = document.getElementById('step2-icon');
            const step3Icon = document.getElementById('step3-icon');
            const planOptions = document.querySelectorAll('.plan-option');

            // Check if there's an old selected plan from validation errors
            if (selectedPlanInput.value) {
                step1Button.disabled = false;
                
                // Mark the correct plan as selected
                planOptions.forEach(plan => {
                    if (plan.dataset.plan === selectedPlanInput.value) {
                        if (!plan.classList.contains('plan-option[data-plan="professional"]')) {
                            plan.classList.remove('border', 'border-gray-200');
                            plan.classList.add('border-blue-600', 'border-2');
                        }
                    }
                });
                
                // If we have validation errors, show the appropriate step
                if (document.querySelector('.bg-red-100')) {
                    const errors = @json($errors->getMessages());
                    
                    // Determine which step to show based on error fields
                    if (errors.email || errors.name || errors.password) {
                        // Show step 2
                        currentStepInput.value = 2;
                        step1.classList.add('hidden');
                        step2.classList.remove('hidden');
                        progressBar.style.width = '66%';
                        
                        step1Indicator.classList.remove('step-active');
                        step1Indicator.classList.add('step-completed');
                        step2Indicator.classList.remove('step-inactive');
                        step2Indicator.classList.add('step-active');
                    } else if (errors.store_name || errors.business_type) {
                        // Show step 3
                        currentStepInput.value = 3;
                        step1.classList.add('hidden');
                        step3.classList.remove('hidden');
                        progressBar.style.width = '100%';
                        
                        step1Indicator.classList.remove('step-active');
                        step1Indicator.classList.add('step-completed');
                        step3Indicator.classList.remove('step-inactive');
                        step3Indicator.classList.add('step-active');
                        
                        step2Indicator.classList.remove('step-inactive');
                        step2Indicator.classList.add('step-completed');
                        step2Icon.classList.remove('fa-user');
                        step2Icon.classList.add('fa-check');
                    }
                }
            } else {
                // Disable step1 button by default
                step1Button.disabled = true;
            }

            // Handle plan selection
            planOptions.forEach(plan => {
                plan.addEventListener('click', function() {
                    // Remove active class from all plans
                    planOptions.forEach(p => {
                        p.classList.remove('border-blue-600', 'border-2');
                        if (!p.classList.contains('plan-option[data-plan="professional"]')) {
                            p.classList.add('border', 'border-gray-200');
                        }
                    });
                    
                    // Add active class to selected plan
                    if (!this.classList.contains('plan-option[data-plan="professional"]')) {
                        this.classList.remove('border', 'border-gray-200');
                        this.classList.add('border-blue-600', 'border-2');
                    }
                    
                    // Update selected plan
                    selectedPlanInput.value = this.dataset.plan;
                    
                    // Enable step1 button
                    step1Button.disabled = false;
                });
            });

            // Go to step 2
            step1Button.addEventListener('click', function() {
                if (selectedPlanInput.value) {
                    // Update current step
                    currentStepInput.value = '2';
                    
                    // Update UI
                    step1.classList.add('hidden');
                    step2.classList.remove('hidden');
                    progressBar.style.width = '66%';
                    
                    // Update indicators
                    step1Indicator.classList.remove('step-active');
                    step1Indicator.classList.add('step-completed');
                    step2Indicator.classList.remove('step-inactive');
                    step2Indicator.classList.add('step-active');
                }
            });

            // Go to step 3
            step2Button.addEventListener('click', function() {
                // Validate user details
                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;
                
                if (name && email && password && passwordConfirmation && password === passwordConfirmation) {
                    // Update current step
                    currentStepInput.value = '3';
                    
                    // Update UI
                    step2.classList.add('hidden');
                    step3.classList.remove('hidden');
                    progressBar.style.width = '100%';
                    
                    // Update indicators
                    step2Indicator.classList.remove('step-active');
                    step2Indicator.classList.add('step-completed');
                    step3Indicator.classList.remove('step-inactive');
                    step3Indicator.classList.add('step-active');
                    
                    // Update icons
                    step2Icon.classList.remove('fa-user');
                    step2Icon.classList.add('fa-check');
                }
            });

            // Go back to step 1
            backToStep1.addEventListener('click', function() {
                // Update current step
                currentStepInput.value = '1';
                
                // Update UI
                step2.classList.add('hidden');
                step1.classList.remove('hidden');
                progressBar.style.width = '33%';
                
                // Update indicators
                step2Indicator.classList.remove('step-active');
                step2Indicator.classList.add('step-inactive');
                step1Indicator.classList.remove('step-completed');
                step1Indicator.classList.add('step-active');
            });

            // Go back to step 2
            backToStep2.addEventListener('click', function() {
                // Update current step
                currentStepInput.value = '2';
                
                // Update UI
                step3.classList.add('hidden');
                step2.classList.remove('hidden');
                progressBar.style.width = '66%';
                
                // Update indicators
                step3Indicator.classList.remove('step-active');
                step3Indicator.classList.add('step-inactive');
                step2Indicator.classList.remove('step-completed');
                step2Indicator.classList.add('step-active');
                
                // Update icons
                step2Icon.classList.remove('fa-check');
                step2Icon.classList.add('fa-user');
            });
        });
    </script>
</body>
</html> 