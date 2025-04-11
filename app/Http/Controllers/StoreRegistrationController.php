<?php

namespace App\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;

class StoreRegistrationController extends Controller
{
    protected $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    /**
     * Display the store registration form
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('central.registration.create-store');
    }

    /**
     * Register a new store and user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'store_name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:domains,domain|regex:/^[a-z0-9\-]+$/',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Create the store with the user_id properly stored in the data array
        $store = $this->storeService->createStore([
            'name' => $request->store_name,
            'data' => [
                'user_id' => $user->id,
            ],
        ]);

        // Create the domain for the store
        $domain = $request->domain . '.' . config('tenancy.central_domains.0', 'connectcommerce.test');
        $store->domains()->create(['domain' => $domain]);

        // Login the user manually on the central domain
        Auth::login($user);
        
        // Make sure to regenerate the session for this login
        $request->session()->regenerate();

        // Continue to onboarding on the central domain
        return redirect()->route('store.onboarding');
    }

    /**
     * Display the store onboarding form
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showOnboarding()
    {
        $store = $this->storeService->getStoreByOwnerId(Auth::id());
        
        if (!$store) {
            return redirect()->route('login')->with('error', 'No store found for your account.');
        }

        return view('central.registration.onboarding', [
            'store' => $store
        ]);
    }

    /**
     * Complete the store onboarding process
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeOnboarding(Request $request)
    {
        $store = $this->storeService->getStoreByOwnerId(Auth::id());
        
        if (!$store) {
            return redirect()->route('login')->with('error', 'No store found for your account.');
        }

        $request->validate([
            'logo' => 'nullable|image|max:1024',
            'description' => 'required|string|max:500',
            'contact_email' => 'required|email',
            'business_type' => 'required|string|max:255',
        ]);

        // Handle logo upload if provided
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Get the existing data
        $data = $store->data;
        
        // Add the new fields to the data array
        $data['logo'] = $logoPath;
        $data['description'] = $request->description;
        $data['contact_email'] = $request->contact_email;
        $data['business_type'] = $request->business_type;
        $data['onboarding_completed'] = true;

        // Update store with the complete data array
        $this->storeService->updateStore([
            'data' => $data
        ], $store->id);

        // Get the store domain if available
        if ($store->domains->isNotEmpty()) {
            $domain = $store->domains->first()->domain;
            
            // Redirect to the tenant domain with a success message
            $protocol = $request->secure() ? 'https://' : 'http://';
            return redirect()->to($protocol . $domain . '/login')
                ->with('status', 'store-created')
                ->with('message', 'Your store has been set up successfully! Please log in to access your dashboard.');
        }

        // If no domain is set up, redirect to the central domain
        $centralDomain = config('tenancy.central_domains.0', 'connectcommerce.test');
        return redirect()->to("https://$centralDomain")->with('success', 'Your store has been set up successfully!');
    }
} 