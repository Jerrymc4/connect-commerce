<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\PaymentMethodRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Customer;

class AccountController extends Controller
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;
    
    /**
     * @var PaymentMethodRepositoryInterface
     */
    protected $paymentMethodRepository;
    
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;
    
    /**
     * AccountController constructor.
     *
     * @param OrderRepositoryInterface $orderRepository
     * @param PaymentMethodRepositoryInterface $paymentMethodRepository
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->customerRepository = $customerRepository;
    }
    
    /**
     * Show the customer account dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        
        // Get recent orders
        $recentOrders = $this->orderRepository->getRecentOrdersByCustomerId($customer->id, 3);
            
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
        
        // Use the customer repository to update the profile
        $this->customerRepository->updateProfile($customer, $validated);
        
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
                if (!Auth::guard('customer')->attempt(['email' => $customer->email, 'password' => $value])) {
                    $fail('The current password is incorrect.');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        // Use the customer repository to update the password
        $this->customerRepository->updatePassword($customer, $validated['password']);
        
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
        $orders = $this->orderRepository->getPaginatedOrdersByCustomerId($customer->id, 10);
            
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
        $order = $this->orderRepository->getCustomerOrder($id, $customer->id);
        
        if (!$order) {
            return redirect()->route('customer.orders')
                ->with('error', 'The requested order could not be found.');
        }
            
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
        $paymentMethods = $this->paymentMethodRepository->getByCustomerId($customer->id);
        
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
        $paymentMethodData = [
            'customer_id' => $customer->id,
            'card_type' => $this->detectCardType($validated['card_number']),
            'last_four' => substr($validated['card_number'], -4),
            'holder_name' => $validated['card_holder'],
            'expiry_month' => $validated['expiry_month'],
            'expiry_year' => $validated['expiry_year'],
            'is_default' => $request->has('is_default'),
        ];
        
        $paymentMethod = $this->paymentMethodRepository->create($paymentMethodData);
        
        if ($paymentMethod->is_default) {
            // Make all other payment methods non-default
            $this->paymentMethodRepository->setAsDefault($paymentMethod);
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
        $paymentMethod = $this->paymentMethodRepository->getCustomerPaymentMethod($id, $customer->id);
        
        if (!$paymentMethod) {
            return redirect()->route('customer.payment-methods')
                ->with('error', 'The payment method could not be found.');
        }
            
        $this->paymentMethodRepository->delete($paymentMethod);
        
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