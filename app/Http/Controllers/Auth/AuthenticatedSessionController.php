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
        xdebug_break();
        $request->authenticate();
        $request->session()->regenerate();

        try {
            $user = Auth::user();
            $store = $this->storeService->getStoreByOwnerId($user->id);

            if ($store && $store->domains->isNotEmpty()) {
                $domain = $store->domains->first()->domain;

                $protocol = app()->environment('production') ? 'https' : 'http';
                return redirect()->away("https://{$domain}/dashboard");
            }
        } catch (\Exception $e) {
            Log::error('Error redirecting to store dashboard: ' . $e->getMessage());
        }

        // Fallback to central dashboard
        return redirect()->away(('https://connectcommerce.test'));
    }

    /**
     * Log out the user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        xdebug_break();
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Optional redirect override
        if ($request->has('redirect_to')) {
            $redirectUrl = $request->input('redirect_to');
            $centralDomain = config('tenancy.central_domains.0', 'connectcommerce.test');

            if (
                filter_var($redirectUrl, FILTER_VALIDATE_URL) &&
                (str_contains($redirectUrl, $centralDomain) || str_contains($redirectUrl, '.connectcommerce.test'))
            ) {
                return redirect($redirectUrl);
            }
        }

        $centralDomain = config('tenancy.central_domains.0', 'connectcommerce.test');
        return redirect("https://$centralDomain");
    }
}
