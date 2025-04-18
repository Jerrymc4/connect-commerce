<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Services\AuditLogService;

class ProductImageController extends Controller
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
     * Display the image management view for a product.
     */
    public function index(Product $product)
    {
        // Check if the authenticated user is the owner of this product
        if (Gate::denies('manage', $product)) {
            abort(403);
        }
        
        return view('store.products.images', compact('product'));
    }
    
    /**
     * Upload a new image for the product.
     */
    public function upload(Request $request, Product $product)
    {
        // Check if the authenticated user is the owner of this product
        if (Gate::denies('manage', $product)) {
            abort(403);
        }
        
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt_text' => 'nullable|string|max:255',
        ]);
        
        try {
            $file = $request->file('image');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Process the image using PHP GD
            $image = $this->resizeImage($file->getRealPath(), 800);
            
            // Save image
            $path = 'products/' . $product->id . '/' . $filename;
            Storage::disk('public')->put($path, $image);
            
            // Get the next sort order
            $maxSortOrder = $product->images()->max('sort_order') ?? 0;
            
            // Create the image record
            $image = new ProductImage([
                'product_id' => $product->id,
                'path' => $path,
                'alt_text' => $validated['alt_text'] ?? $product->name,
                'sort_order' => $maxSortOrder + 1,
                'is_main' => $product->images()->count() === 0,
            ]);
            
            $image->save();
            
            // Log to audit trail
            $this->auditLogService->logCreated($image, ['module' => 'product_images']);
            
            return redirect()->route('admin.products.images', $product)
                ->with('success', 'Image uploaded successfully');
        } catch (\Exception $e) {
            Log::error('Failed to upload product image: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to upload image: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Resize an image using PHP GD library.
     *
     * @param string $sourcePath Path to the source image
     * @param int $maxWidth Maximum width for the resized image
     * @return string Binary image data
     */
    private function resizeImage($sourcePath, $maxWidth)
    {
        // Get image info
        list($width, $height, $type) = getimagesize($sourcePath);
        
        // Calculate new dimensions
        $newWidth = min($width, $maxWidth);
        $newHeight = ($height * $newWidth) / $width;
        
        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Load source image based on type
        switch ($type) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($sourcePath);
                // Preserve transparency
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            default:
                throw new \Exception('Unsupported image type');
        }
        
        // Resize the image
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Start output buffering
        ob_start();
        
        // Output the image to the buffer
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($newImage, null, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($newImage, null, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($newImage);
                break;
        }
        
        // Get the image data
        $imageData = ob_get_clean();
        
        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($newImage);
        
        return $imageData;
    }
    
    /**
     * Update image details.
     */
    public function update(Request $request, Product $product, ProductImage $image)
    {
        // Check if the authenticated user is the owner of this product
        if (Gate::denies('manage', $product)) {
            abort(403);
        }
        
        $validated = $request->validate([
            'alt_text' => 'required|string|max:255',
        ]);
        
        try {
            // Store original values for audit log
            $originalValues = $image->getAttributes();
            
            $image->alt_text = $validated['alt_text'];
            $image->save();
            
            // Log to audit trail
            $this->auditLogService->logUpdated($image, $originalValues, ['module' => 'product_images']);
            
            return redirect()->route('admin.products.images', $product)
                ->with('success', 'Image updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update product image: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update image: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Delete a product image.
     */
    public function destroy(Product $product, ProductImage $image)
    {
        // Check if the authenticated user is the owner of this product
        if (Gate::denies('manage', $product)) {
            abort(403);
        }
        
        try {
            // If deleting the main image, set the first remaining image as main
            if ($image->is_main) {
                $newMainImage = $product->images()
                    ->where('id', '!=', $image->id)
                    ->orderBy('sort_order')
                    ->first();
                
                if ($newMainImage) {
                    $newMainImage->is_main = true;
                    $newMainImage->save();
                }
            }
            
            // Log to audit trail before deleting
            $this->auditLogService->logDeleted($image, ['module' => 'product_images']);
            
            // Delete the image file
            Storage::disk('public')->delete($image->path);
            
            // Delete the database record
            $image->delete();
            
            // Reorder remaining images
            $this->reorderImages($product);
            
            return redirect()->route('admin.products.images', $product)
                ->with('success', 'Image deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete product image: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete image: ' . $e->getMessage());
        }
    }
    
    /**
     * Set an image as the main product image.
     */
    public function setMain(Product $product, ProductImage $image)
    {
        // Check if the authenticated user is the owner of this product
        if (Gate::denies('manage', $product)) {
            abort(403);
        }
        
        try {
            // Store original values for audit log
            $originalValues = [];
            
            // Get all product images and log changes for each
            $images = $product->images;
            foreach ($images as $img) {
                if ($img->is_main !== ($img->id === $image->id)) {
                    $originalValues[$img->id] = ['is_main' => $img->is_main];
                }
            }
            
            // Remove main flag from all other images
            $product->images()->update(['is_main' => false]);
            
            // Set this image as main
            $image->is_main = true;
            $image->save();
            
            // Log to audit trail
            $this->auditLogService->logUpdated($image, $originalValues, ['module' => 'product_images']);
            
            return redirect()->route('admin.products.images', $product)
                ->with('success', 'Main image updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to set main product image: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to set main image: ' . $e->getMessage());
        }
    }
    
    /**
     * Reorder product images.
     */
    public function reorder(Request $request, Product $product)
    {
        // Check if the authenticated user is the owner of this product
        if (Gate::denies('manage', $product)) {
            abort(403);
        }
        
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'integer|exists:product_images,id',
        ]);
        
        try {
            // Store original values for audit log
            $originalValues = [];
            $images = $product->images()->get()->keyBy('id');
            
            $imageIds = $request->images;
            foreach ($imageIds as $index => $id) {
                if (isset($images[$id]) && $images[$id]->sort_order !== ($index + 1)) {
                    $originalValues[$id] = ['sort_order' => $images[$id]->sort_order];
                }
                
                ProductImage::where('id', $id)->update(['sort_order' => $index + 1]);
            }
            
            // Log to audit trail if changes were made
            if (!empty($originalValues)) {
                $this->auditLogService->logUpdated(new ProductImage(), $originalValues, ['module' => 'product_images']);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to reorder product images: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Helper method to reorder images after deletion.
     */
    private function reorderImages(Product $product)
    {
        $images = $product->images()->orderBy('sort_order')->get();
        foreach ($images as $index => $image) {
            $image->sort_order = $index + 1;
            $image->save();
        }
    }
} 