<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\AuditLogService;
use Illuminate\View\View;
use Illuminate\Support\Str;

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
            'slug' => 'nullable|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Generate a slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }
        
        $category = $this->categoryRepository->create($validated);
        
        // Log category creation to audit log
        $this->auditLogService->logCreated($category, ['module' => 'categories']);
        
        return redirect()->route('store.settings', ['tab' => 'categories'])
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
        ]);
        
        // Generate a slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        // Prevent self-reference for parent_id
        if (isset($validated['parent_id']) && $validated['parent_id'] == $id) {
            $validated['parent_id'] = null;
        }
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }
        
        $this->categoryRepository->update($category, $validated);
        
        // Log category update to audit log
        $this->auditLogService->logUpdated($category, $originalValues, ['module' => 'categories']);
        
        return redirect()->route('store.settings', ['tab' => 'categories'])
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
            return redirect()->route('store.settings', ['tab' => 'categories'])
                ->with('error', 'Cannot delete category with sub-categories. Please delete sub-categories first.');
        }
        
        // Check if category has products
        if ($category->products->isNotEmpty()) {
            return redirect()->route('store.settings', ['tab' => 'categories'])
                ->with('error', 'Cannot delete category with associated products. Please remove the products first or change their category.');
        }
        
        // Log category deletion to audit log before deleting
        $this->auditLogService->logDeleted($category, ['module' => 'categories']);
        
        $this->categoryRepository->delete($category);
        
        return redirect()->route('store.settings', ['tab' => 'categories'])
            ->with('success', 'Category deleted successfully');
    }
} 