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
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('tenant.login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('tenant.password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('tenant.password.email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('tenant.password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('tenant.password.store');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/', fn () => view('storefront.home', ['tenant' => tenant('id'), 'storeName' => tenant()->name]))->name('storefront.home');
        
        // Dashboard route for the tenant
        Route::get('/dashboard', function () {
            return view('dashboard', [
                'tenant' => tenant('id'), 
                'storeName' => tenant()->name
            ]);
        })->name('store.dashboard');

        Route::get('verify-email', EmailVerificationPromptController::class)->name('tenant.verification.notice');
        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('tenant.verification.verify');
        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('tenant.verification.send');
        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('tenant.password.confirm');
        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
        Route::put('password', [PasswordController::class, 'update'])->name('tenant.password.update');
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('tenant.logout');
    });
});
