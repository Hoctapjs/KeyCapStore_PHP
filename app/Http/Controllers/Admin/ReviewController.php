<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     */
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }
        
        $reviews = $query->latest()->paginate(20);
        
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Approve a review.
     */
    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);
        
        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Đánh giá đã được duyệt.');
    }

    /**
     * Reject a review.
     */
    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);
        
        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Đánh giá đã bị từ chối.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();
        
        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Đánh giá đã được xóa.');
    }
}
