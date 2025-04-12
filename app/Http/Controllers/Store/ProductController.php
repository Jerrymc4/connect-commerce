<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\AuditLogService;
use Illuminate\View\View;

class ProductController extends Controller
{
    protected AuditLogService $auditLogService;
    
    /**
     * Create a new controller instance.
     *
     * @param AuditLogService $auditLogService
     */
    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }
    
    /**
     * Display a listing of products.
     */
    public function index(): View
    {
        $products = Product::latest()->paginate(10);
        return view('store.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        return view('store.products.form');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products',
            'slug' => 'required|string|max:255|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,draft,out_of_stock',
            'stock' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'track_inventory' => 'boolean',
        ]);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }
        
        $product = Product::create($validated);
        
        // Log product creation to audit log
        $this->auditLogService->logCreated($product, ['module' => 'products']);
        
        return redirect()->route('store.products')
            ->with('success', 'Product created successfully');
    }

    /**
     * Display the specified product.
     */
    public function show(string $id): View
    {
        $product = Product::findOrFail($id);
        return view('store.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(string $id): View
    {
        $product = Product::findOrFail($id);
        return view('store.products.form', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        
        // Store original values for audit log
        $originalValues = $product->getAttributes();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $id,
            'slug' => 'required|string|max:255|unique:products,slug,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,draft,out_of_stock',
            'stock' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'track_inventory' => 'boolean',
        ]);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }
        
        $product->update($validated);
        
        // Log product update to audit log
        $this->auditLogService->logUpdated($product, $originalValues, ['module' => 'products']);
        
        return redirect()->route('store.products')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        
        // Log product deletion to audit log before deleting
        $this->auditLogService->logDeleted($product, ['module' => 'products']);
        
        $product->delete();
        
        return redirect()->route('store.products')
            ->with('success', 'Product deleted successfully');
    }
} 