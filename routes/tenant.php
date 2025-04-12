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
    
    // Store Admin Dashboard - All authenticated routes
    Route::middleware(['auth'])->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('store.dashboard');
        
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
