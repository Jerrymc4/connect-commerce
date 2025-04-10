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

        $store = $this->storeService->createStore([
            'name' => $request->store_name,
            'user_id' => $user->id,
        ]);

        Auth::login($user);

        return redirect()->route('store.onboarding');
    }

    /**
     * Display the store onboarding form
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function onboarding()
    {
        $store = $this->storeService->getStoreByOwnerId(Auth::id());
        
        if (!$store) {
            return redirect()->route('dashboard')->with('error', 'No store found for your account.');
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
            return redirect()->route('dashboard')->with('error', 'No store found for your account.');
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

        // Update store details
        $this->storeService->updateStore([
            'logo' => $logoPath,
            'description' => $request->description,
            'contact_email' => $request->contact_email,
            'business_type' => $request->business_type,
            'onboarding_completed' => true,
        ], $store->id);

        return redirect()->route('dashboard')->with('success', 'Your store has been set up successfully!');
    }
} 