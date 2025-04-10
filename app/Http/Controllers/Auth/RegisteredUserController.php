<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use App\Services\TenantProvisioner;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'selected_plan' => 'required|string|in:starter,professional,enterprise',
            'store_name' => 'required|string|max:255',
            'business_type' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Create store with auto-generated domain via TenantProvisioner
            $store = TenantProvisioner::create(
                $request->store_name,
                $user->id
            );
            
            // Merge the plan and business type with the existing data JSON that contains user_id
            $currentData = $store->data ?? [];
            $storeData = array_merge($currentData, [
                'plan' => $request->selected_plan,
                'business_type' => $request->business_type,
            ]);
            
            $store->data = $storeData;
            $store->save();

            event(new Registered($user));

            // Don't log the user in - redirect to tenant login page instead
            // Auth::login($user);
            
            // Get the tenant's domain
            $storeDomain = $store->domains->first()->domain;
            
            // Redirect to the tenant's login page with a success message
            return redirect()->to("https://{$storeDomain}/login")->with([
                'status' => 'store-created',
                'message' => 'Your store has been successfully created! Please log in to access your dashboard.'
            ]);
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return back()->withErrors(['registration' => 'Registration failed: ' . $e->getMessage()])->withInput();
        }
    }
}
