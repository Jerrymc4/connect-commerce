<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class StoreFinderController extends Controller
{
    /**
     * Show the store finder form.
     */
    public function show()
    {
        return view('store-finder');
    }

    /**
     * Handle the store search and redirect.
     */
    public function find(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|min:2',
        ]);

        $searchTerm = trim($request->input('store_name'));
        $slug = Str::slug($searchTerm);

        $store = Store::query()
            ->whereJsonContains('data->name', $searchTerm)
            ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(data, "$.name"))) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
            ->orWhere('id', 'like', "{$slug}-%")
            ->first();

        if (!$store) {
            Log::warning('Store not found', ['searchTerm' => $searchTerm]);
            return back()->withErrors([
                'store_name' => 'We couldn’t find a store with that name. Please try again or contact support.',
            ]);
        }

        $store->load('domains');

        if ($store->domains->isEmpty()) {
            Log::warning('Store found but no domains', ['storeId' => $store->id]);
            return back()->withErrors([
                'store_name' => 'This store exists but is not fully set up yet. Please contact support.',
            ]);
        }

        $domain = $store->domains->first()->domain;
        $loginUrl = "https://{$domain}/login";

        Log::info('Redirecting to tenant domain', [
            'storeId' => $store->id,
            'redirectTo' => $loginUrl,
        ]);

        // For htmx or API clients
        if ($request->expectsJson()) {
            return response()->json([
                'redirect' => true,
                'url' => $loginUrl,
            ])->header('HX-Redirect', $loginUrl);
        }

        return redirect()->away($loginUrl);
    }
}
