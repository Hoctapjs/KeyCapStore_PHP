<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Show the form for creating a new review.
     */
    public function create($productId)
    {
        $product = Product::with(['productImages', 'brand'])->findOrFail($productId);
        
        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $productId)
            ->where('user_id', Auth::id())
            ->first();
            
        if ($existingReview) {
            return redirect()
                ->route('products.show', $product->slug)
                ->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }
        
        return view('products.review', compact('product'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.min' => 'Đánh giá phải từ 1 đến 5 sao.',
            'rating.max' => 'Đánh giá phải từ 1 đến 5 sao.',
            'title.required' => 'Vui lòng nhập tiêu đề đánh giá.',
            'title.max' => 'Tiêu đề không được quá 255 ký tự.',
            'content.required' => 'Vui lòng nhập nội dung đánh giá.',
            'content.min' => 'Nội dung đánh giá phải có ít nhất 10 ký tự.',
        ]);

        $product = Product::findOrFail($productId);
        
        // Check if user already reviewed
        $existingReview = Review::where('product_id', $productId)
            ->where('user_id', Auth::id())
            ->first();
            
        if ($existingReview) {
            return redirect()
                ->route('products.show', $product->slug)
                ->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        Review::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'content' => $request->content,
            'status' => 'approved', // Auto-approve new reviews
        ]);

        return redirect()
            ->route('products.show', $product->slug)
            ->with('success', 'Cảm ơn bạn đá đánh giá! Đánh giá của bạn đã được hiển thị.');
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(Review $review)
    {
        // Only allow user to edit their own review
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, Review $review)
    {
        // Only allow user to edit their own review
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
        ]);

        // If review was rejected, set to pending for re-approval
        // If already approved, keep it approved
        $newStatus = $review->status === 'rejected' ? 'pending' : 'approved';

        $review->update([
            'rating' => $request->rating,
            'title' => $request->title,
            'content' => $request->content,
            'status' => $newStatus,
        ]);

        $message = $newStatus === 'pending' 
            ? 'Đánh giá của bạn đã được cập nhật và đang chờ duyệt lại.'
            : 'Đánh giá của bạn đã được cập nhật!';

        return redirect()
            ->route('products.show', $review->product->slug)
            ->with('success', $message);
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        // Only allow user to delete their own review or admin
        if ($review->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        $productSlug = $review->product->slug;
        $review->delete();

        return redirect()
            ->route('products.show', $productSlug)
            ->with('success', 'Đánh giá đã được xóa.');
    }
}
