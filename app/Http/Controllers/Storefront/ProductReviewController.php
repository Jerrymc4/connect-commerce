<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the reviews for a product.
     */
    public function index(Product $product)
    {
        $reviews = $product->reviews()->with('customer')->orderBy('created_at', 'desc')->paginate(10);
        
        return view('storefront.products.reviews', [
            'product' => $product,
            'reviews' => $reviews
        ]);
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:1000',
        ]);

        // Check if user is logged in
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')
                ->with('error', 'You must be logged in to leave a review.');
        }

        // Check if user has already reviewed this product
        $existingReview = ProductReview::where('product_id', $product->id)
            ->where('customer_id', Auth::guard('customer')->id())
            ->first();

        if ($existingReview) {
            // Update existing review
            $existingReview->update([
                'rating' => $request->rating,
                'review' => $request->review,
            ]);

            return redirect()->back()
                ->with('success', 'Your review has been updated.');
        }

        // Create new review
        ProductReview::create([
            'product_id' => $product->id,
            'customer_id' => Auth::guard('customer')->id(),
            'rating' => $request->rating,
            'review' => $request->review,
            'status' => 'approved', // Auto-approve for now, you might want to moderate in the future
        ]);

        return redirect()->back()
            ->with('success', 'Your review has been submitted. Thank you!');
    }
} 