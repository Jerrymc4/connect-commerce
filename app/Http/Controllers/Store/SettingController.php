<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display the store settings form.
     */
    public function index(): View
    {
        // Get the current tenant/store
        $store = tenant();
        
        // Get store settings from store data
        $storeSettings = $store->data['settings'] ?? [
            'general' => [
                'store_name' => $store->name,
                'store_email' => $store->owner_email ?? '',
                'store_phone' => $store->owner_phone ?? '',
                'store_address' => '',
                'legal_name' => '',
                'tax_id' => '',
            ],
            'regional' => [
                'currency' => 'USD',
                'weight_unit' => 'kg',
                'dimension_unit' => 'cm',
                'timezone' => 'UTC',
                'date_format' => 'MM/DD/YYYY',
            ],
            'checkout' => [
                'guest_checkout' => true,
                'terms_and_conditions' => true,
                'terms_text' => 'I agree to the terms and conditions.',
                'order_notes' => true,
            ],
            'shipping' => [
                'free_shipping_enabled' => false,
                'free_shipping_threshold' => 100,
                'shipping_origin_address' => '',
                'shipping_origin_city' => '',
                'shipping_origin_state' => '',
                'shipping_origin_zip' => '',
                'shipping_origin_country' => 'US',
            ],
            'tax' => [
                'tax_calculation' => 'automatic',
                'tax_included_in_prices' => false,
                'default_tax_rate' => 0,
                'tax_display' => 'excluding',
            ],
            'email' => [
                'sender_name' => $store->name,
                'sender_email' => $store->owner_email ?? '',
                'order_confirmation' => true,
                'order_shipped' => true,
                'order_refunded' => true,
                'welcome_email' => true,
                'email_header' => '',
                'email_footer' => '',
            ],
            'privacy' => [
                'cookie_notice' => true,
                'cookie_message' => 'This store uses cookies to enhance your shopping experience.',
                'privacy_policy' => '',
                'terms_of_service' => '',
                'refund_policy' => '',
            ],
        ];
        
        // Get currency options
        $currencies = [
            'USD' => 'US Dollar ($)',
            'EUR' => 'Euro (€)',
            'GBP' => 'British Pound (£)',
            'CAD' => 'Canadian Dollar (C$)',
            'AUD' => 'Australian Dollar (A$)',
            'JPY' => 'Japanese Yen (¥)',
        ];
        
        // Get weight unit options
        $weightUnits = [
            'kg' => 'Kilograms (kg)',
            'g' => 'Grams (g)',
            'lb' => 'Pounds (lb)',
            'oz' => 'Ounces (oz)',
        ];
        
        // Get dimension unit options
        $dimensionUnits = [
            'cm' => 'Centimeters (cm)',
            'mm' => 'Millimeters (mm)',
            'in' => 'Inches (in)',
            'ft' => 'Feet (ft)',
        ];
        
        // Get timezone options (simplified list)
        $timezones = [
            'UTC' => 'UTC',
            'America/New_York' => 'Eastern Time (ET)',
            'America/Chicago' => 'Central Time (CT)',
            'America/Denver' => 'Mountain Time (MT)',
            'America/Los_Angeles' => 'Pacific Time (PT)',
            'Europe/London' => 'Greenwich Mean Time (GMT)',
            'Europe/Paris' => 'Central European Time (CET)',
        ];
        
        // Get country options (simplified list)
        $countries = [
            'US' => 'United States',
            'CA' => 'Canada',
            'GB' => 'United Kingdom',
            'AU' => 'Australia',
            'DE' => 'Germany',
            'FR' => 'France',
            'JP' => 'Japan',
        ];
        
        return view('store.settings', compact(
            'storeSettings', 
            'currencies', 
            'weightUnits', 
            'dimensionUnits', 
            'timezones', 
            'countries'
        ));
    }

    /**
     * Update the store settings.
     */
    public function update(Request $request)
    {
        // Get the current tenant/store
        $store = tenant();
        $storeData = $store->data ?? [];
        
        // Build validation rules based on the form structure
        $validated = $request->validate([
            'general.store_name' => 'required|string|max:255',
            'general.store_email' => 'required|email|max:255',
            'general.store_phone' => 'nullable|string|max:20',
            'general.store_address' => 'nullable|string',
            'general.legal_name' => 'nullable|string|max:255',
            'general.tax_id' => 'nullable|string|max:100',
            
            'regional.currency' => 'required|string|max:3',
            'regional.weight_unit' => 'required|string|max:10',
            'regional.dimension_unit' => 'required|string|max:10',
            'regional.timezone' => 'required|string',
            'regional.date_format' => 'required|string|max:20',
            
            'checkout.guest_checkout' => 'boolean',
            'checkout.terms_and_conditions' => 'boolean',
            'checkout.terms_text' => 'nullable|string',
            'checkout.order_notes' => 'boolean',
            
            'shipping.free_shipping_enabled' => 'boolean',
            'shipping.free_shipping_threshold' => 'nullable|numeric|min:0',
            'shipping.shipping_origin_address' => 'nullable|string',
            'shipping.shipping_origin_city' => 'nullable|string|max:100',
            'shipping.shipping_origin_state' => 'nullable|string|max:100',
            'shipping.shipping_origin_zip' => 'nullable|string|max:20',
            'shipping.shipping_origin_country' => 'required|string|max:2',
            
            'tax.tax_calculation' => 'required|string|in:automatic,manual',
            'tax.tax_included_in_prices' => 'boolean',
            'tax.default_tax_rate' => 'nullable|numeric|min:0',
            'tax.tax_display' => 'required|string|in:including,excluding',
            
            'email.sender_name' => 'required|string|max:255',
            'email.sender_email' => 'required|email|max:255',
            'email.order_confirmation' => 'boolean',
            'email.order_shipped' => 'boolean',
            'email.order_refunded' => 'boolean',
            'email.welcome_email' => 'boolean',
            'email.email_header' => 'nullable|string',
            'email.email_footer' => 'nullable|string',
            
            'privacy.cookie_notice' => 'boolean',
            'privacy.cookie_message' => 'nullable|string',
            'privacy.privacy_policy' => 'nullable|string',
            'privacy.terms_of_service' => 'nullable|string',
            'privacy.refund_policy' => 'nullable|string',
        ]);
        
        // Update the store name if it has changed
        if ($validated['general']['store_name'] !== $store->name) {
            $store->name = $validated['general']['store_name'];
        }
        
        // Update store settings in data field
        $storeData['settings'] = $validated;
        $store->data = $storeData;
        $store->save();
        
        return redirect()->route('store.settings')
            ->with('success', 'Store settings saved successfully');
    }
} 