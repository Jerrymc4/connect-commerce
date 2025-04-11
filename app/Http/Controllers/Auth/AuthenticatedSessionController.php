<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use App\Services\StoreService;

class AuthenticatedSessionController extends Controller
{
    protected StoreService $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    /**
     * Show the login view.
     */
    public function create(): View
    {
        return view('auth.login', [
            'canResetPassword' => route('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        
        // Regenerate the session properly
        $request->session()->regenerate();
        
        // Get current host and context
        $currentHost = $request->getHost();
        $centralDomain = config('tenancy.central_domains.0', 'connectcommerce.test');
        $isCentralDomain = $currentHost === $centralDomain;

        // If on tenant domain, go to the dashboard
        if (!$isCentralDomain) {
            return redirect('/dashboard');
        }
        
        // If on central domain, redirect to landing page
        return redirect('/');
    }

    /**
     * Log out the user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Simply redirect to login
        return redirect('/login');
    }
}
