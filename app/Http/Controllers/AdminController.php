<?php

namespace App\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Store;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    protected StoreService $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    /**
     * Show the admin dashboard.
     */
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'totalRevenue' => 0, // Replace with actual data from your service
            'totalOrders' => 0,  // Replace with actual data from your service
            'totalProducts' => 0, // Replace with actual data from your service
            'totalCustomers' => 0, // Replace with actual data from your service
        ]);
    }

    /**
     * Show the products page.
     */
    public function products(): View
    {
        return view('admin.products', [
            'products' => [], // Replace with actual data from your service
            'productsCount' => 0, // Replace with actual data from your service
        ]);
    }

    /**
     * Show the product form.
     */
    public function productForm($id = null): View
    {
        $product = null; // Get product by ID if $id exists

        return view('admin.products-form', [
            'product' => $product,
        ]);
    }

    /**
     * View a single product.
     */
    public function productView($id): View
    {
        $product = null; // Get product by ID

        return view('admin.product-view', [
            'product' => $product,
        ]);
    }

    /**
     * Store a new product.
     */
    public function storeProduct(Request $request)
    {
        // Validate and store product
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'status' => 'required|string|in:active,draft,out_of_stock',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            // Add other validation rules as needed
        ]);

        // Save product
        // $product = ProductService::createProduct($validated);

        return redirect()->route('admin.products')->with('success', 'Product created successfully');
    }

    /**
     * Update an existing product.
     */
    public function updateProduct(Request $request, $id)
    {
        // Validate and update product
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'status' => 'required|string|in:active,draft,out_of_stock',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            // Add other validation rules as needed
        ]);

        // Update product
        // $product = ProductService::updateProduct($id, $validated);

        return redirect()->route('admin.products')->with('success', 'Product updated successfully');
    }

    /**
     * Delete a product.
     */
    public function deleteProduct($id)
    {
        // Delete product
        // ProductService::deleteProduct($id);

        return redirect()->route('admin.products')->with('success', 'Product deleted successfully');
    }

    /**
     * Show the orders page.
     */
    public function orders(): View
    {
        return view('admin.orders', [
            'orders' => [], // Replace with actual data from your service
            'ordersCount' => 0, // Replace with actual data from your service
            'pendingOrders' => 0,
            'processingOrders' => 0,
            'completedOrders' => 0,
            'cancelledOrders' => 0,
        ]);
    }

    /**
     * View a single order.
     */
    public function orderView($id): View
    {
        $order = null; // Get order by ID

        return view('admin.order-view', [
            'order' => $order,
        ]);
    }

    /**
     * Edit an order.
     */
    public function orderEdit($id): View
    {
        $order = null; // Get order by ID

        return view('admin.order-edit', [
            'order' => $order,
        ]);
    }

    /**
     * View order invoice.
     */
    public function orderInvoice($id): View
    {
        $order = null; // Get order by ID

        return view('admin.order-invoice', [
            'order' => $order,
        ]);
    }

    /**
     * Update an order.
     */
    public function updateOrder(Request $request, $id)
    {
        // Validate and update order
        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled,refunded',
            // Add other validation rules as needed
        ]);

        // Update order
        // $order = OrderService::updateOrder($id, $validated);

        return redirect()->route('admin.orders')->with('success', 'Order updated successfully');
    }

    /**
     * Show the customers page.
     */
    public function customers(): View
    {
        return view('admin.customers', [
            'customers' => [], // Replace with actual data from your service
            'customersCount' => 0, // Replace with actual data from your service
            'activeCustomers' => 0,
            'newCustomers' => 0,
            'repeatCustomers' => 0,
        ]);
    }

    /**
     * Show customer form (create/edit).
     */
    public function customerForm($id = null): View
    {
        $customer = null; // Get customer by ID if $id exists

        return view('admin.customer-form', [
            'customer' => $customer,
        ]);
    }

    /**
     * View a single customer.
     */
    public function customerView($id): View
    {
        $customer = null; // Get customer by ID

        return view('admin.customer-view', [
            'customer' => $customer,
        ]);
    }

    /**
     * Store a new customer.
     */
    public function storeCustomer(Request $request)
    {
        // Validate and store customer
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            // Add other validation rules as needed
        ]);

        // Save customer
        // $customer = CustomerService::createCustomer($validated);

        return redirect()->route('admin.customers')->with('success', 'Customer created successfully');
    }

    /**
     * Update an existing customer.
     */
    public function updateCustomer(Request $request, $id)
    {
        // Validate and update customer
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,'.$id,
            'phone' => 'nullable|string|max:20',
            // Add other validation rules as needed
        ]);

        // Update customer
        // $customer = CustomerService::updateCustomer($id, $validated);

        return redirect()->route('admin.customers')->with('success', 'Customer updated successfully');
    }

    /**
     * Delete a customer.
     */
    public function deleteCustomer($id)
    {
        // Delete customer
        // CustomerService::deleteCustomer($id);

        return redirect()->route('admin.customers')->with('success', 'Customer deleted successfully');
    }

    /**
     * Show the settings page.
     */
    public function settings(): View
    {
        $settings = (object) [
            'store_name' => 'Your Store',
            'store_email' => 'store@example.com',
            'store_phone' => '+1 (555) 123-4567',
            'store_address' => '123 Commerce St, Anytown, USA',
            'store_description' => 'Your store description goes here.',
            'store_logo' => null,
            'currency' => 'USD',
            'weight_unit' => 'lb',
            'dimension_unit' => 'in',
            'send_order_emails' => true,
            'send_shipping_emails' => true,
            'send_inventory_emails' => false,
        ];

        return view('admin.settings', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update store settings.
     */
    public function updateSettings(Request $request)
    {
        // Validate request
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_email' => 'required|email|max:255',
            'store_phone' => 'nullable|string|max:20',
            'store_address' => 'nullable|string|max:255',
            'store_description' => 'nullable|string|max:1000',
            'store_logo' => 'nullable|image|max:2048',
            'currency' => 'required|string|in:USD,EUR,GBP,CAD,AUD',
            'weight_unit' => 'nullable|string|in:kg,g,lb,oz',
            'dimension_unit' => 'nullable|string|in:cm,m,in,ft',
            'send_order_emails' => 'nullable|boolean',
            'send_shipping_emails' => 'nullable|boolean',
            'send_inventory_emails' => 'nullable|boolean',
        ]);

        // Handle logo upload if present
        if ($request->hasFile('store_logo')) {
            // Process and store the logo
        }

        // Update settings
        // Implementation depends on your settings storage approach

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully');
    }

    /**
     * Show the theme customization page
     */
    public function theme(): View
    {
        // Get the current store
        $store = Store::find(request()->tenant->id);
        
        // Get theme settings
        $theme = $store->data['theme'] ?? [];
        
        return view('admin.theme', compact('theme'));
    }
    
    /**
     * Update theme settings
     */
    public function updateTheme(Request $request)
    {
        // Get the current store
        $store = Store::find(request()->tenant->id);
        
        // Validate theme settings
        $validated = $request->validate([
            'primary_color' => 'nullable|string|max:25',
            'secondary_color' => 'nullable|string|max:25',
            'background_color' => 'nullable|string|max:25',
            'text_color' => 'nullable|string|max:25',
            'heading_font' => 'nullable|string|max:50',
            'body_font' => 'nullable|string|max:50',
            'base_font_size' => 'nullable|string|max:10',
            'heading_weight' => 'nullable|string|max:10',
            'header_layout' => 'nullable|string|max:25',
            'header_color' => 'nullable|string|max:25',
            'show_search' => 'nullable|boolean',
            'sticky_header' => 'nullable|boolean',
            'show_announcements' => 'nullable|boolean',
            'announcement_text' => 'nullable|string|max:255',
            'footer_layout' => 'nullable|string|max:25',
            'footer_color' => 'nullable|string|max:25',
            'show_payment_icons' => 'nullable|boolean',
            'show_social_icons' => 'nullable|boolean',
            'show_newsletter_signup' => 'nullable|boolean',
            'footer_text' => 'nullable|string|max:255',
            'custom_css' => 'nullable|string',
        ]);
        
        // Update store data
        $data = $store->data ?? [];
        $data['theme'] = $validated;
        
        // Save to database
        $store->data = $data;
        $store->save();
        
        // Generate and update CSS file
        $this->generateThemeCSS($store);
        
        return redirect()
            ->route('admin.theme')
            ->with('success', 'Theme settings updated successfully!');
    }
    
    /**
     * Generate CSS from theme settings
     */
    private function generateThemeCSS(Store $store)
    {
        $theme = $store->data['theme'] ?? [];
        
        // Base CSS
        $css = "/* Generated Theme CSS */\n";
        
        // Add variables
        $css .= ":root {\n";
        $css .= "  --primary-color: " . ($theme['primary_color'] ?? '#3B82F6') . ";\n";
        $css .= "  --secondary-color: " . ($theme['secondary_color'] ?? '#10B981') . ";\n";
        $css .= "  --background-color: " . ($theme['background_color'] ?? '#F9FAFB') . ";\n";
        $css .= "  --text-color: " . ($theme['text_color'] ?? '#1F2937') . ";\n";
        $css .= "  --header-color: " . ($theme['header_color'] ?? '#FFFFFF') . ";\n";
        $css .= "  --footer-color: " . ($theme['footer_color'] ?? '#1F2937') . ";\n";
        $css .= "  --heading-font: " . ($theme['heading_font'] ?? 'Inter') . ", sans-serif;\n";
        $css .= "  --body-font: " . ($theme['body_font'] ?? 'Inter') . ", sans-serif;\n";
        $css .= "  --base-font-size: " . ($theme['base_font_size'] ?? '16px') . ";\n";
        $css .= "  --heading-weight: " . ($theme['heading_weight'] ?? '700') . ";\n";
        $css .= "}\n\n";
        
        // Add base styles
        $css .= "body {\n";
        $css .= "  font-family: var(--body-font);\n";
        $css .= "  font-size: var(--base-font-size);\n";
        $css .= "  color: var(--text-color);\n";
        $css .= "  background-color: var(--background-color);\n";
        $css .= "}\n\n";
        
        $css .= "h1, h2, h3, h4, h5, h6 {\n";
        $css .= "  font-family: var(--heading-font);\n";
        $css .= "  font-weight: var(--heading-weight);\n";
        $css .= "}\n\n";
        
        $css .= ".btn-primary {\n";
        $css .= "  background-color: var(--primary-color);\n";
        $css .= "  border-color: var(--primary-color);\n";
        $css .= "}\n\n";
        
        $css .= ".btn-secondary {\n";
        $css .= "  background-color: var(--secondary-color);\n";
        $css .= "  border-color: var(--secondary-color);\n";
        $css .= "}\n\n";
        
        // Header styles
        $css .= "header {\n";
        $css .= "  background-color: var(--header-color);\n";
        if (($theme['sticky_header'] ?? false)) {
            $css .= "  position: sticky;\n";
            $css .= "  top: 0;\n";
            $css .= "  z-index: 100;\n";
        }
        $css .= "}\n\n";
        
        // Footer styles
        $css .= "footer {\n";
        $css .= "  background-color: var(--footer-color);\n";
        $css .= "  color: white;\n";
        $css .= "}\n\n";
        
        // Add custom CSS
        if (!empty($theme['custom_css'])) {
            $css .= "/* Custom CSS */\n";
            $css .= $theme['custom_css'] . "\n";
        }
        
        // Create CSS file (in a real app, you would save this to the public assets)
        // For demo purposes, we'll just log it
        \Log::info("Generated CSS for store {$store->id}", ['css' => $css]);
        
        return $css;
    }

    /**
     * Show the payments configuration page.
     */
    public function payments(): View
    {
        return view('admin.payments');
    }

    /**
     * Display a list of all stores.
     */
    public function stores(): View
    {
        // Get all stores with counts for associated products, orders, and customers
        $stores = Store::withCount(['products', 'orders', 'customers'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get counts for dashboard stats
        $totalStores = Store::count();
        $activeStores = Store::where('status', 'active')->count();
        $newStores = Store::where('created_at', '>=', now()->subDays(30))->count();
        $totalProducts = Product::count();
        
        return view('admin.stores', compact(
            'stores', 
            'totalStores', 
            'activeStores', 
            'newStores', 
            'totalProducts'
        ));
    }

    /**
     * Show the form for creating/editing a store.
     */
    public function storeForm($id = null): View
    {
        $store = $id ? Store::findOrFail($id) : null;
        $users = User::all(); // Get all users for the dropdown
        
        return view('admin.stores-form', compact('store', 'users'));
    }

    /**
     * Display detailed information about a specific store.
     */
    public function storeView($id): View
    {
        $store = Store::with('user')
            ->withCount(['products', 'orders', 'customers'])
            ->findOrFail($id);
        
        // Get recent orders for this store
        $recentOrders = Order::where('store_id', $id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Calculate total revenue for this store
        $store->total_revenue = Order::where('store_id', $id)
            ->where('status', 'completed')
            ->sum('total');
        
        // Get plan name based on plan ID
        $planNames = [
            1 => 'Basic - $29/month',
            2 => 'Professional - $79/month',
            3 => 'Business - $299/month',
            4 => 'Enterprise - Custom',
        ];
        $store->plan_name = $planNames[$store->plan_id] ?? 'Unknown Plan';
        
        return view('admin.store-view', compact('store', 'recentOrders'));
    }

    /**
     * Store a newly created store in the database.
     */
    public function storeStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:stores,domain',
            'custom_domain' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,inactive,pending',
            'description' => 'nullable|string',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'user_id' => 'nullable|exists:users,id',
            'plan_id' => 'required|integer|in:1,2,3,4',
            'trial_ends_at' => 'nullable|date',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'send_welcome_email' => 'nullable|boolean',
        ]);
        
        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('store-logos', 'public');
        }
        
        // Create store
        $store = Store::create($validated);
        
        // Send welcome email if requested
        if ($request->boolean('send_welcome_email')) {
            // TODO: Implement welcome email functionality
        }
        
        return redirect()
            ->route('admin.stores.view', $store->id)
            ->with('success', 'Store created successfully!');
    }

    /**
     * Update the specified store in the database.
     */
    public function updateStore(Request $request, $id)
    {
        $store = Store::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:stores,domain,' . $id,
            'custom_domain' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,inactive,pending',
            'description' => 'nullable|string',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'user_id' => 'nullable|exists:users,id',
            'plan_id' => 'required|integer|in:1,2,3,4',
            'trial_ends_at' => 'nullable|date',
            'features' => 'nullable|array',
            'features.*' => 'string',
        ]);
        
        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            
            $validated['logo'] = $request->file('logo')->store('store-logos', 'public');
        }
        
        // Update store
        $store->update($validated);
        
        return redirect()
            ->route('admin.stores.view', $store->id)
            ->with('success', 'Store updated successfully!');
    }

    /**
     * Remove the specified store from the database.
     */
    public function deleteStore($id)
    {
        $store = Store::findOrFail($id);
        
        // Delete logo if it exists
        if ($store->logo) {
            Storage::disk('public')->delete($store->logo);
        }
        
        // Delete store
        $store->delete();
        
        return redirect()
            ->route('admin.stores')
            ->with('success', 'Store deleted successfully!');
    }
} 