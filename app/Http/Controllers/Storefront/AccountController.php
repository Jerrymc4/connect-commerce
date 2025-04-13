<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Customer;
use App\Models\Order;
use App\Models\PaymentMethod;

class AccountController extends Controller
{
    /**
     * Show the customer account dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        
        // Get recent orders
        $recentOrders = Order::where('customer_id', $customer->id)
            ->latest()
            ->take(3)
            ->get();
            
        return view('storefront.account.index', compact('customer', 'recentOrders'));
    }
    
    /**
     * Show the profile edit form.
     *
     * @return \Illuminate\View\View
     */
    public function editProfile()
    {
        $customer = Auth::guard('customer')->user();
        return view('storefront.account.profile', compact('customer'));
    }
    
    /**
     * Update the customer profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('customers')->ignore($customer->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);
        
        $customer->update($validated);
        
        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Show the change password form.
     *
     * @return \Illuminate\View\View
     */
    public function editPassword()
    {
        return view('storefront.account.password');
    }
    
    /**
     * Update the customer's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        
        $validated = $request->validate([
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($customer) {
                if (!Hash::check($value, $customer->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        $customer->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()->route('customer.password')->with('success', 'Password updated successfully.');
    }
    
    /**
     * Show the customer's orders history.
     *
     * @return \Illuminate\View\View
     */
    public function orders()
    {
        $customer = Auth::guard('customer')->user();
        $orders = Order::where('customer_id', $customer->id)
            ->latest()
            ->paginate(10);
            
        return view('storefront.account.orders', compact('orders'));
    }
    
    /**
     * Show a specific order details.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showOrder($id)
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::where('customer_id', $customer->id)
            ->where('id', $id)
            ->firstOrFail();
            
        return view('storefront.account.order-detail', compact('order'));
    }
    
    /**
     * Show the customer's addresses.
     *
     * @return \Illuminate\View\View
     */
    public function addresses()
    {
        $customer = Auth::guard('customer')->user();
        $addresses = $customer->addresses;
        
        return view('storefront.account.addresses', compact('addresses'));
    }
    
    /**
     * Show the customer's payment methods.
     *
     * @return \Illuminate\View\View
     */
    public function paymentMethods()
    {
        $customer = Auth::guard('customer')->user();
        $paymentMethods = PaymentMethod::where('customer_id', $customer->id)->get();
        
        return view('storefront.account.payment-methods', compact('paymentMethods'));
    }
    
    /**
     * Show the form to add a new payment method.
     *
     * @return \Illuminate\View\View
     */
    public function addPaymentMethod()
    {
        return view('storefront.account.add-payment-method');
    }
    
    /**
     * Store a new payment method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePaymentMethod(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        
        $validated = $request->validate([
            'card_number' => ['required', 'string', 'min:13', 'max:19'],
            'card_holder' => ['required', 'string', 'max:255'],
            'expiry_month' => ['required', 'numeric', 'min:1', 'max:12'],
            'expiry_year' => ['required', 'numeric', 'min:' . date('Y'), 'max:' . (date('Y') + 20)],
            'cvv' => ['required', 'numeric', 'min:100', 'max:9999'],
            'is_default' => ['sometimes', 'boolean'],
        ]);
        
        // In a real app, this would involve a payment processor integration
        // Here we're just storing the data (which you shouldn't do with real card data)
        $paymentMethod = new PaymentMethod();
        $paymentMethod->customer_id = $customer->id;
        $paymentMethod->card_type = $this->detectCardType($validated['card_number']);
        $paymentMethod->last_four = substr($validated['card_number'], -4);
        $paymentMethod->holder_name = $validated['card_holder'];
        $paymentMethod->expiry_month = $validated['expiry_month'];
        $paymentMethod->expiry_year = $validated['expiry_year'];
        $paymentMethod->is_default = $request->has('is_default');
        $paymentMethod->save();
        
        if ($paymentMethod->is_default) {
            // Make all other payment methods non-default
            PaymentMethod::where('customer_id', $customer->id)
                ->where('id', '!=', $paymentMethod->id)
                ->update(['is_default' => false]);
        }
        
        return redirect()->route('customer.payment-methods')->with('success', 'Payment method added successfully.');
    }
    
    /**
     * Delete a payment method.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePaymentMethod($id)
    {
        $customer = Auth::guard('customer')->user();
        $paymentMethod = PaymentMethod::where('customer_id', $customer->id)
            ->where('id', $id)
            ->firstOrFail();
            
        $paymentMethod->delete();
        
        return redirect()->route('customer.payment-methods')->with('success', 'Payment method removed successfully.');
    }
    
    /**
     * Detect the card type based on the card number.
     *
     * @param  string  $cardNumber
     * @return string
     */
    private function detectCardType($cardNumber)
    {
        // Very basic detection - in a real app, this would be more comprehensive
        if (preg_match('/^4/', $cardNumber)) {
            return 'Visa';
        } elseif (preg_match('/^5[1-5]/', $cardNumber)) {
            return 'Mastercard';
        } elseif (preg_match('/^3[47]/', $cardNumber)) {
            return 'American Express';
        } elseif (preg_match('/^6(?:011|5)/', $cardNumber)) {
            return 'Discover';
        } else {
            return 'Unknown';
        }
    }
} 