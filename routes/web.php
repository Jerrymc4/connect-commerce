<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Fallback route to redirect to configured central domain
Route::fallback(function () {
    return redirect(config('tenancy.central_domains.0'));
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Stores Management
    Route::get('/stores', [AdminController::class, 'stores'])->name('stores');
    Route::get('/stores/create', [AdminController::class, 'storeForm'])->name('stores.create');
    Route::get('/stores/{id}/edit', [AdminController::class, 'storeForm'])->name('stores.edit');
    Route::get('/stores/{id}', [AdminController::class, 'storeView'])->name('stores.view');
    Route::post('/stores', [AdminController::class, 'storeStore'])->name('stores.store');
    Route::put('/stores/{id}', [AdminController::class, 'updateStore'])->name('stores.update');
    Route::delete('/stores/{id}', [AdminController::class, 'deleteStore'])->name('stores.delete');
    
    // Products Management
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'productForm'])->name('products.create');
    Route::get('/products/{id}/edit', [AdminController::class, 'productForm'])->name('products.edit');
    Route::get('/products/{id}', [AdminController::class, 'productView'])->name('products.view');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');
    
    // Orders Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AdminController::class, 'orderView'])->name('orders.view');
    Route::get('/orders/{id}/edit', [AdminController::class, 'orderEdit'])->name('orders.edit');
    Route::get('/orders/{id}/invoice', [AdminController::class, 'orderInvoice'])->name('orders.invoice');
    Route::put('/orders/{id}', [AdminController::class, 'updateOrder'])->name('orders.update');
    
    // Customer Management
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::get('/customers/create', [AdminController::class, 'customerForm'])->name('customers.create');
    Route::get('/customers/{id}/edit', [AdminController::class, 'customerForm'])->name('customers.edit');
    Route::get('/customers/{id}', [AdminController::class, 'customerView'])->name('customers.view');
    Route::post('/customers', [AdminController::class, 'storeCustomer'])->name('customers.store');
    Route::put('/customers/{id}', [AdminController::class, 'updateCustomer'])->name('customers.update');
    Route::delete('/customers/{id}', [AdminController::class, 'deleteCustomer'])->name('customers.delete');
    
    // Store Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/theme', [AdminController::class, 'theme'])->name('theme');
    Route::put('/theme', [AdminController::class, 'updateTheme'])->name('theme.update');
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
