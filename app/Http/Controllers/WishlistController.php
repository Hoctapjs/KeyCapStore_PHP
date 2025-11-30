<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->wishlists()
            ->with(['product.productImages', 'product.brand', 'product.categories']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('product.categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $query->whereHas('product.brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Filter by price range
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('product', function($q) use ($request) {
                if ($request->filled('min_price')) {
                    $q->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $q->where('price', '<=', $request->max_price);
                }
            });
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->join('products', 'wishlists.product_id', '=', 'products.id')
                      ->orderBy('products.price', 'asc')
                      ->select('wishlists.*');
                break;
            case 'price_desc':
                $query->join('products', 'wishlists.product_id', '=', 'products.id')
                      ->orderBy('products.price', 'desc')
                      ->select('wishlists.*');
                break;
            case 'name_asc':
                $query->join('products', 'wishlists.product_id', '=', 'products.id')
                      ->orderBy('products.title', 'asc')
                      ->select('wishlists.*');
                break;
            case 'name_desc':
                $query->join('products', 'wishlists.product_id', '=', 'products.id')
                      ->orderBy('products.title', 'desc')
                      ->select('wishlists.*');
                break;
            default:
                $query->latest();
                break;
        }

        $wishlists = $query->paginate(12)->withQueryString();
        
        // Get categories and brands for filters
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $brands = Brand::orderBy('name')->get();

        return view('wishlist.index', compact('wishlists', 'categories', 'brands'));
    }

    public function toggle(Product $product)
    {
        $user = Auth::user();
        
        $wishlist = Wishlist::where('user_id', $user->id)
                           ->where('product_id', $product->id)
                           ->first();

        if ($wishlist) {
            // Remove from wishlist
            $wishlist->delete();
            $inWishlist = false;
            $message = 'Đã xóa khỏi danh sách yêu thích';
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $inWishlist = true;
            $message = 'Đã thêm vào danh sách yêu thích';
        }

        // Get updated count
        $count = Wishlist::where('user_id', $user->id)->count();

        return response()->json([
            'success' => true,
            'in_wishlist' => $inWishlist,
            'count' => $count,
            'message' => $message,
        ]);
    }

    public function count()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Wishlist::where('user_id', Auth::id())->count();
        return response()->json(['count' => $count]);
    }

    public function remove(Product $product)
    {
        $deleted = Wishlist::where('user_id', Auth::id())
                          ->where('product_id', $product->id)
                          ->delete();

        if ($deleted) {
            return redirect()->route('wishlist.index')
                           ->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích');
        }

        return back()->with('error', 'Không thể xóa sản phẩm');
    }
}
