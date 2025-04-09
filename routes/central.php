<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Central Domain Routes
|--------------------------------------------------------------------------
|
| Here you can register routes for your central domain.
| These routes will not have tenant context and are separate from tenant routes.
|
*/

// Central domain route - simplified for debugging
Route::get('/', function () {
    return 'Central domain root route works!';
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Add other central domain routes here 