<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\TenantAssetController;
use App\Http\Controllers\Store\DashboardController;
use App\Http\Controllers\Store\ProductController;
use App\Http\Controllers\Store\OrderController;
use App\Http\Controllers\Store\CustomerController;
use App\Http\Controllers\Store\ThemeController;
use App\Http\Controllers\Store\SettingController;
use App\Http\Controllers\Store\DiscountController;
use App\Http\Controllers\Store\CategoryController;
use App\Http\Controllers\Store\AuditLogController;
use App\Http\Controllers\Store\ProductImageController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Storefront\ProductController as StorefrontProductController;
use App\Http\Controllers\Storefront\CategoryController as StorefrontCategoryController;
use App\Http\Controllers\Auth\CustomerAuthController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// Route to serve assets for tenants
Route::get('build/{path}', [TenantAssetController::class, 'serveAsset'])
    ->where('path', '.*')
    ->middleware('web')
    ->name('tenant.asset');

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Route to serve tenant storage files
    Route::get('storage/{path}', function ($path) {

        $tenantId = tenant()->id;
        $filePath = storage_path("app/public/$path");

        
        // if (!file_exists($filePath)) {
        //     abort(404);
        // }
        
        $contentType = mime_content_type($filePath);
        
        return response(file_get_contents($filePath), 200)
            ->header('Content-Type', $contentType);
    })->where('path', '.*')
      ->name('tenant.storage');

    Route::middleware('guest')->group(function () {
        Route::get('admin/login', [AuthenticatedSessionController::class, 'create'])->name('tenant.login');
        Route::post('admin/login', [AuthenticatedSessionController::class, 'store']);
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('tenant.password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('tenant.password.email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('tenant.password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('tenant.password.store');
    });

    // Storefront routes accessible without authentication
    Route::middleware(['theme.settings'])->group(function() {
        Route::get('/', [App\Http\Controllers\Storefront\HomeController::class, 'index'])->name('storefront.home');

        // Product routes
        Route::get('/products', [App\Http\Controllers\Storefront\ProductController::class, 'index'])->name('storefront.products.index');
        Route::get('/products/{slug}', [App\Http\Controllers\Storefront\ProductController::class, 'show'])->name('storefront.products.show');
        
        // Product review routes
        Route::post('/products/{product}/review', [App\Http\Controllers\Storefront\ProductReviewController::class, 'store'])->name('storefront.products.review');
        Route::get('/products/{product}/reviews', [App\Http\Controllers\Storefront\ProductReviewController::class, 'index'])->name('storefront.products.reviews');

        // Shopping Cart Routes
        Route::get('/cart', [App\Http\Controllers\Storefront\CartController::class, 'index'])->name('storefront.cart');
        Route::post('/cart/add', [App\Http\Controllers\Storefront\CartController::class, 'add'])->name('storefront.cart.add');
        Route::post('/cart/update', [App\Http\Controllers\Storefront\CartController::class, 'update'])->name('storefront.cart.update');
        Route::post('/cart/remove', [App\Http\Controllers\Storefront\CartController::class, 'remove'])->name('storefront.cart.remove');
        Route::post('/cart/clear', [App\Http\Controllers\Storefront\CartController::class, 'clear'])->name('storefront.cart.clear');
        
        // Checkout Routes
        Route::get('/checkout', [App\Http\Controllers\Storefront\CheckoutController::class, 'index'])->name('storefront.checkout');
        Route::post('/checkout/process', [App\Http\Controllers\Storefront\CheckoutController::class, 'process'])->name('storefront.checkout.process');

        // Customer Authentication Routes
        Route::get('/login', function() {
            return view('storefront.auth.login');
        })->name('customer.login');

        Route::post('/login', function(Illuminate\Http\Request $request) {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended(route('storefront.home'));
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        })->name('customer.login.attempt');

        // Customer Registration Routes
        Route::get('/register', function() {
            return view('storefront.auth.register');
        })->name('customer.register');

        Route::post('/register', function(Illuminate\Http\Request $request) {
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:customers'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'phone' => ['nullable', 'string', 'max:20'],
                'terms' => ['required', 'accepted'],
            ]);

            $customer = \App\Models\Customer::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'phone' => $validatedData['phone'] ?? null,
            ]);

            Auth::guard('customer')->login($customer);
            
            return redirect()->route('storefront.home');
        })->name('customer.register.store');

        Route::post('/customer/logout', function(Illuminate\Http\Request $request) {
            Auth::guard('customer')->logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('storefront.home');
        })->name('customer.logout');

        // Customer Account Routes (protected by customer auth)
        Route::middleware(['auth:customer'])->group(function() {
            // Account Dashboard
            Route::get('/account', [\App\Http\Controllers\Storefront\AccountController::class, 'index'])
                ->name('customer.account');
            
            // Profile Management
            Route::get('/account/profile', [\App\Http\Controllers\Storefront\AccountController::class, 'editProfile'])
                ->name('customer.profile');
            Route::put('/account/profile', [\App\Http\Controllers\Storefront\AccountController::class, 'updateProfile'])
                ->name('customer.profile.update');
            
            // Password Management
            Route::get('/account/password', [\App\Http\Controllers\Storefront\AccountController::class, 'editPassword'])
                ->name('customer.password');
            Route::put('/account/password', [\App\Http\Controllers\Storefront\AccountController::class, 'updatePassword'])
                ->name('customer.password.update');
            
            // Orders History
            Route::get('/account/orders', [\App\Http\Controllers\Storefront\AccountController::class, 'orders'])
                ->name('customer.orders');
            Route::get('/account/orders/{id}', [\App\Http\Controllers\Storefront\AccountController::class, 'showOrder'])
                ->name('customer.orders.show');
            
            // Address Management
            Route::get('/account/addresses', [\App\Http\Controllers\Storefront\AccountController::class, 'addresses'])
                ->name('customer.addresses');
            Route::get('/account/addresses/add', [\App\Http\Controllers\Storefront\AccountController::class, 'addAddress'])
                ->name('customer.addresses.create');
            Route::post('/account/addresses', [\App\Http\Controllers\Storefront\AccountController::class, 'storeAddress'])
                ->name('customer.addresses.store');
            Route::get('/account/addresses/{id}/edit', [\App\Http\Controllers\Storefront\AccountController::class, 'editAddress'])
                ->name('customer.addresses.edit');
            Route::put('/account/addresses/{id}', [\App\Http\Controllers\Storefront\AccountController::class, 'updateAddress'])
                ->name('customer.addresses.update');
            Route::delete('/account/addresses/{id}', [\App\Http\Controllers\Storefront\AccountController::class, 'deleteAddress'])
                ->name('customer.addresses.destroy');
            
            // Payment Methods
            Route::get('/account/payment-methods', [\App\Http\Controllers\Storefront\AccountController::class, 'paymentMethods'])
                ->name('customer.payment-methods');
            Route::get('/account/payment-methods/add', [\App\Http\Controllers\Storefront\AccountController::class, 'addPaymentMethod'])
                ->name('customer.payment-methods.create');
            Route::post('/account/payment-methods', [\App\Http\Controllers\Storefront\AccountController::class, 'storePaymentMethod'])
                ->name('customer.payment-methods.store');
            Route::delete('/account/payment-methods/{id}', [\App\Http\Controllers\Storefront\AccountController::class, 'deletePaymentMethod'])
                ->name('customer.payment-methods.destroy');
            
            // Wishlist
            Route::get('/account/wishlist', [\App\Http\Controllers\Storefront\AccountController::class, 'wishlist'])
                ->name('customer.wishlist');
        });
    });

    // Store Admin Dashboard - All authenticated routes
    Route::middleware(['auth'])->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Preview Store - redirects to storefront home
        Route::get('/preview-store', function() {
            return redirect()->route('storefront.home');
        })->name('store.preview');
        
        // Products Management
        Route::get('/products', [ProductController::class, 'index'])->name('admin.products');
        Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
        Route::get('/products/{id}', [ProductController::class, 'show'])->name('admin.products.show');
        Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
        
        // Product Images Management
        Route::get('/products/{productId}/images', [ProductImageController::class, 'index'])->name('admin.products.images');
        Route::post('/products/{productId}/images', [ProductImageController::class, 'store'])->name('admin.products.images.store');
        Route::put('/products/{productId}/images/{imageId}', [ProductImageController::class, 'update'])->name('admin.products.images.update');
        Route::delete('/products/{productId}/images/{imageId}', [ProductImageController::class, 'destroy'])->name('admin.products.images.destroy');
        Route::post('/products/{productId}/images/{imageId}/main', [ProductImageController::class, 'setMain'])->name('admin.products.images.main');
        Route::post('/products/{productId}/images/reorder', [ProductImageController::class, 'reorder'])->name('admin.products.images.reorder');
        
        // Orders Management
        Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
        Route::put('/orders/{id}', [OrderController::class, 'update'])->name('admin.orders.update');
        Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])->name('admin.orders.invoice');
        
        // Customers Management
        Route::get('/customers', [CustomerController::class, 'index'])->name('admin.customers');
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('admin.customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('admin.customers.store');
        Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('admin.customers.show');
        Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('admin.customers.edit');
        Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('admin.customers.update');
        Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('admin.customers.destroy');
        
        // Audit Logs Management
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('admin.audit-logs.index');
        Route::get('/audit-logs/{id}', [AuditLogController::class, 'show'])->name('admin.audit-logs.show');
        Route::get('/audit-logs/export', [AuditLogController::class, 'export'])->name('admin.audit-logs.export');
        
        // Integrated Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
        Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
        Route::get('/test-flash', [SettingController::class, 'testFlash'])->name('admin.test-flash');
        
        // Categories Management
        Route::get('/settings/categories/create', [CategoryController::class, 'create'])->name('admin.settings.categories.create');
        Route::post('/settings/categories', [CategoryController::class, 'store'])->name('admin.settings.categories.store');
        Route::get('/settings/categories/{id}/edit', [CategoryController::class, 'edit'])->name('admin.settings.categories.edit');
        Route::put('/settings/categories/{id}', [CategoryController::class, 'update'])->name('admin.settings.categories.update');
        Route::delete('/settings/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.settings.categories.destroy');
        
        // Discounts Management
        Route::post('/settings/discounts', [SettingController::class, 'storeDiscount'])->name('admin.settings.discounts.store');
        Route::put('/settings/discounts/{id}', [SettingController::class, 'updateDiscount'])->name('admin.settings.discounts.update');
        Route::delete('/settings/discounts/{id}', [SettingController::class, 'destroyDiscount'])->name('admin.settings.discounts.destroy');
        
        // Old routes - these will redirect to the new settings page with the appropriate tab
        Route::get('/theme', function() { 
            return redirect()->route('admin.settings', ['tab' => 'theme']); 
        })->name('admin.themes');
        
        // Theme reset route
        Route::get('/settings/theme/reset', [App\Http\Controllers\Store\ThemeController::class, 'reset'])
            ->name('admin.settings.theme.reset');
        
        Route::get('/discounts', function() {
            return redirect()->route('admin.settings', ['tab' => 'discounts']); 
        })->name('admin.discounts');
        
        Route::get('/discounts/create', function() {
            return redirect()->route('admin.settings', ['tab' => 'discounts', 'action' => 'create']); 
        })->name('admin.discounts.create');
        
        Route::get('/discounts/{id}/edit', function($id) {
            return redirect()->route('admin.settings', ['tab' => 'discounts', 'action' => 'edit', 'id' => $id]); 
        })->name('admin.discounts.edit');
    });

    // User account routes
    Route::middleware(['auth'])->group(function () {
        Route::get('verify-email', EmailVerificationPromptController::class)->name('tenant.verification.notice');
        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('tenant.verification.verify');
        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('tenant.verification.send');
        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('tenant.password.confirm');
        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
        Route::put('password', [PasswordController::class, 'update'])->name('tenant.password.update');
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('tenant.logout');
    });

    // Store routes (authenticated store owner routes)
    Route::middleware(['auth', 'role:store_owner'])->prefix('store')->name('store.')->group(function () {
        // Product images routes
        Route::get('products/{product}/images', [App\Http\Controllers\Store\ProductImageController::class, 'index'])
            ->name('products.images');
        Route::post('products/{product}/images/upload', [App\Http\Controllers\Store\ProductImageController::class, 'upload'])
            ->name('products.images.upload');
        Route::put('products/{product}/images/{image}', [App\Http\Controllers\Store\ProductImageController::class, 'update'])
            ->name('products.images.update');
        Route::get('products/{product}/images/{image}/delete', [App\Http\Controllers\Store\ProductImageController::class, 'destroy'])
            ->name('products.images.delete');
        Route::get('products/{product}/images/{image}/main', [App\Http\Controllers\Store\ProductImageController::class, 'setMain'])
            ->name('products.images.main');
        Route::post('products/{product}/images/reorder', [App\Http\Controllers\Store\ProductImageController::class, 'reorder'])
            ->name('products.images.reorder');
    });
});
