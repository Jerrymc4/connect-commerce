<?php

use Illuminate\Support\Facades\Route;

// Fallback: redirect to central domain
Route::get('/', function () {
    return redirect()->to('https://' . config('tenancy.central_domains.0', 'connectcommerce.test'));
});

// Profile fallback to central
Route::middleware('auth')->group(function () {
    Route::get('/profile', fn() => redirect()->to('https://' . config('tenancy.central_domains.0') . '/profile'));
    Route::patch('/profile', fn() => redirect()->to('https://' . config('tenancy.central_domains.0') . '/profile'));
    Route::delete('/profile', fn() => redirect()->to('https://' . config('tenancy.central_domains.0') . '/profile'));
});
