<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\AuditLogService;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * @var AuditLogService
     */
    protected AuditLogService $auditLogService;
    
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;
    
    /**
     * Create a new controller instance.
     *
     * @param AuditLogService $auditLogService
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        AuditLogService $auditLogService,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->auditLogService = $auditLogService;
        $this->categoryRepository = $categoryRepository;
    }
    
    /**
     * Display a listing of the categories.
     */
    public function index(): View
    {
        $categories = $this->categoryRepository->getCategoriesWithChildren();
        return view('store.settings.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        $parentCategories = $this->categoryRepository->getParentCategories();
        return view('store.settings.categories.form', compact('parentCategories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Generate a slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            try {
                // Ensure categories directory exists
                $directory = 'categories';
                Storage::disk('public')->makeDirectory($directory);
                
                // Store the image with a unique filename
                $imagePath = $request->file('image')->store($directory, 'public');
                $validated['image'] = $imagePath;
                
                // Log success for debugging
                Log::info('Category image uploaded successfully', [
                    'path' => $imagePath,
                    'original_name' => $request->file('image')->getClientOriginalName(),
                    'size' => $request->file('image')->getSize(),
                    'tenant_id' => tenant('id')
                ]);
            } catch (\Exception $e) {
                Log::error('Category image upload failed', [
                    'error' => $e->getMessage(),
                    'original_name' => $request->file('image')->getClientOriginalName(),
                    'tenant_id' => tenant('id')
                ]);
                
                return back()->withInput()->with('error', 'Failed to upload image: ' . $e->getMessage());
            }
        }
        
        $category = $this->categoryRepository->create($validated);
        
        // Log to audit trail
        $this->auditLogService->logCreated($category, ['module' => 'categories']);
        
        return redirect()->route('admin.settings', ['tab' => 'categories'])
            ->with('success', 'Category created successfully');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(string $id): View
    {
        $category = $this->categoryRepository->getById($id);
        
        if (!$category) {
            abort(404, 'Category not found');
        }
        
        // Debug logging for the image path
        if ($category->image) {
            Log::debug('Category image path', [
                'category_id' => $category->id,
                'image_path' => $category->image,
                'tenant_asset_url' => tenant_asset($category->image),
                'tenant_id' => tenant('id')
            ]);
        }
        
        $parentCategories = $this->categoryRepository->getParentCategories()->filter(function($item) use ($id) {
            return $item->id != $id;
        });
        
        return view('store.settings.categories.form', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = $this->categoryRepository->getById($id);
        
        if (!$category) {
            abort(404, 'Category not found');
        }
        
        // Store original values for audit log
        $originalValues = $category->getAttributes();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);
        
        // Generate a slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        // Prevent self-reference for parent_id
        if (isset($validated['parent_id']) && $validated['parent_id'] == $id) {
            $validated['parent_id'] = null;
        }
        
        // Handle image removal
        if (isset($validated['remove_image']) && $validated['remove_image'] && $category->image) {
            Storage::disk('public')->delete($category->image);
            $validated['image'] = null;
        } elseif ($request->hasFile('image')) {
            // Ensure categories directory exists
            $directory = 'categories';
            Storage::disk('public')->makeDirectory($directory);
            
            // Delete old image if one exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $imagePath = $request->file('image')->store($directory, 'public');
            $validated['image'] = $imagePath;
        }
        
        // Remove remove_image from validated data before updating
        if (array_key_exists('remove_image', $validated)) {
            unset($validated['remove_image']);
        }
        
        $this->categoryRepository->update($category, $validated);
        
        // Log category update to audit log
        $this->auditLogService->logUpdated($category, $originalValues, ['module' => 'categories']);
        
        return redirect()->route('admin.settings', ['tab' => 'categories'])
            ->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(string $id)
    {
        $category = $this->categoryRepository->getById($id);
        
        if (!$category) {
            abort(404, 'Category not found');
        }
        
        // Check if category has children
        $children = $this->categoryRepository->getChildCategories($id);
        if ($children->isNotEmpty()) {
            return redirect()->route('admin.settings', ['tab' => 'categories'])
                ->with('error', 'Cannot delete category with sub-categories. Please delete sub-categories first.');
        }
        
        // Check if category has products
        if ($category->products->isNotEmpty()) {
            return redirect()->route('admin.settings', ['tab' => 'categories'])
                ->with('error', 'Cannot delete category with associated products. Please remove the products first or change their category.');
        }
        
        // Log category deletion to audit log before deleting
        $this->auditLogService->logDeleted($category, ['module' => 'categories']);
        
        $this->categoryRepository->delete($category);
        
        return redirect()->route('admin.settings', ['tab' => 'categories'])
            ->with('success', 'Category deleted successfully');
    }
} 