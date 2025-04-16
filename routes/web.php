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
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// This file handles routes for the main application domain
// For tenant-specific routes, see routes/tenant.php

Route::get('/', function () {
    return 'Please access a tenant subdomain.';
});

// Fallback route to redirect to configured central domain
Route::fallback(function () {
    return redirect(config('tenancy.central_domains.0'));
});

// Admin Routes


