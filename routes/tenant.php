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
use App\Http\Controllers\Store\AuditLogController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Storefront\ProductController as StorefrontProductController;
use App\Http\Controllers\Storefront\CategoryController;
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

    Route::middleware('guest')->group(function () {
        Route::get('admin/login', [AuthenticatedSessionController::class, 'create'])->name('tenant.login');
        Route::post('admin/login', [AuthenticatedSessionController::class, 'store']);
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('tenant.password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('tenant.password.email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('tenant.password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('tenant.password.store');
    });

    // Storefront routes accessible without authentication
    Route::get('/', fn () => view('storefront.home', ['tenant' => tenant('id'), 'storeName' => tenant()->name]))->name('storefront.home');
    Route::get('/products', [StorefrontProductController::class, 'index'])->name('storefront.products');
    Route::get('/products/{slug}', [StorefrontProductController::class, 'show'])->name('storefront.products.show');
    Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('storefront.categories.show');

    // Shopping Cart Routes
    Route::get('/cart', function () {
        return view('storefront.cart');
    })->name('storefront.cart');

    Route::post('/cart/add', function (Illuminate\Http\Request $request) {
        // Cart add logic will go here
        return response()->json(['success' => true]);
    })->name('storefront.cart.add');

    Route::post('/cart/update', function (Illuminate\Http\Request $request) {
        // Cart update logic
        return response()->json(['success' => true]);
    })->name('storefront.cart.update');

    Route::post('/cart/remove', function (Illuminate\Http\Request $request) {
        // Cart remove item logic
        return response()->json(['success' => true]);
    })->name('storefront.cart.remove');

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
        Route::get('/account', function() {
            return view('storefront.account.index');
        })->name('customer.account');
        
        Route::get('/account/orders', function() {
            return view('storefront.account.orders');
        })->name('customer.orders');
        
        Route::get('/account/wishlist', function() {
            return view('storefront.account.wishlist');
        })->name('customer.wishlist');
        
        Route::get('/account/addresses', function() {
            return view('storefront.account.addresses');
        })->name('customer.addresses');
        
        Route::get('/account/profile', function() {
            return view('storefront.account.profile');
        })->name('customer.profile');
    });

    // Store Admin Dashboard - All authenticated routes
    Route::middleware(['auth'])->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('store.dashboard');
        
        // Preview Store - redirects to storefront home
        Route::get('/preview-store', function() {
            return redirect()->route('storefront.home');
        })->name('store.preview');
        
        // Products Management
        Route::get('/products', [ProductController::class, 'index'])->name('store.products');
        Route::get('/products/create', [ProductController::class, 'create'])->name('store.products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('store.products.store');
        Route::get('/products/{id}', [ProductController::class, 'show'])->name('store.products.show');
        Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('store.products.edit');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('store.products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('store.products.destroy');
        
        // Orders Management
        Route::get('/orders', [OrderController::class, 'index'])->name('store.orders');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('store.orders.show');
        Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('store.orders.edit');
        Route::put('/orders/{id}', [OrderController::class, 'update'])->name('store.orders.update');
        Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])->name('store.orders.invoice');
        
        // Customers Management
        Route::get('/customers', [CustomerController::class, 'index'])->name('store.customers');
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('store.customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('store.customers.store');
        Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('store.customers.show');
        Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('store.customers.edit');
        Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('store.customers.update');
        Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('store.customers.destroy');
        
        // Audit Logs Management
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('store.audit-logs.index');
        Route::get('/audit-logs/{id}', [AuditLogController::class, 'show'])->name('store.audit-logs.show');
        Route::get('/audit-logs/export', [AuditLogController::class, 'export'])->name('store.audit-logs.export');
        
        // Integrated Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('store.settings');
        Route::put('/settings', [SettingController::class, 'update'])->name('store.settings.update');
        Route::post('/settings/discounts', [SettingController::class, 'storeDiscount'])->name('store.settings.discounts.store');
        Route::put('/settings/discounts/{id}', [SettingController::class, 'updateDiscount'])->name('store.settings.discounts.update');
        Route::delete('/settings/discounts/{id}', [SettingController::class, 'destroyDiscount'])->name('store.settings.discounts.destroy');
        
        // Old routes - these will redirect to the new settings page with the appropriate tab
        Route::get('/theme', function() { 
            return redirect()->route('store.settings', ['tab' => 'theme']); 
        })->name('store.themes');
        
        Route::get('/discounts', function() {
            return redirect()->route('store.settings', ['tab' => 'discounts']); 
        })->name('store.discounts');
        
        Route::get('/discounts/create', function() {
            return redirect()->route('store.settings', ['tab' => 'discounts', 'action' => 'create']); 
        })->name('store.discounts.create');
        
        Route::get('/discounts/{id}/edit', function($id) {
            return redirect()->route('store.settings', ['tab' => 'discounts', 'action' => 'edit', 'id' => $id]); 
        })->name('store.discounts.edit');
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
});
