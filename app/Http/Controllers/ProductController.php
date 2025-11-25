<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductTag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->select('id', 'title', 'slug', 'code', 'brand_id', 'price', 'stock', 'status', 'created_at')
            ->with([
                'brand:id,name,slug',
                'productImages' => function($query) {
                    $query->select('id', 'product_id', 'image_url', 'alt')
                        ->orderBy('sort_order')
                        ->limit(1);
                }
            ])
            ->withAvg(['reviews as reviews_avg_rating' => function($query) {
                $query->where('status', 'approved');
            }], 'rating')
            ->where('status', 'active');

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by brand
        if ($request->has('brand')) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Filter by tag
        if ($request->has('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Filter by price range
        if ($request->filled('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);

        // Get filter data - cache for 1 hour
        $brands = cache()->remember('filter_brands', 3600, function() {
            return Brand::select('id', 'name', 'slug')->orderBy('name')->get();
        });
        
        $categories = cache()->remember('filter_categories', 3600, function() {
            return Category::select('id', 'name', 'slug', 'parent_id')
                ->whereNull('parent_id')
                ->with(['children' => function($query) {
                    $query->select('id', 'name', 'slug', 'parent_id');
                }])
                ->get();
        });
        
        $tags = cache()->remember('filter_tags', 3600, function() {
            return ProductTag::select('id', 'name', 'slug')->get();
        });

        return view('products.index', compact('products', 'brands', 'categories', 'tags'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with([
                'brand:id,name,slug',
                'categories:id,name,slug',
                'tags:id,name,slug',
                'variants',
                'productImages' => function($query) {
                    $query->select('id', 'product_id', 'image_url', 'alt', 'sort_order')
                        ->orderBy('sort_order');
                },
                'reviews' => function($query) {
                    $query->select('id', 'product_id', 'user_id', 'rating', 'title', 'content', 'created_at')
                        ->with('user:id,name')
                        ->where('status', 'approved')
                        ->latest()
                        ->limit(10);
                }
            ])
            ->active()
            ->firstOrFail();

        // Get related products (same category) - optimized
        $relatedProducts = Product::select('id', 'title', 'slug', 'price', 'stock')
            ->with(['productImages' => function($query) {
                $query->select('id', 'product_id', 'image_url')->orderBy('sort_order')->limit(1);
            }])
            ->active()
            ->whereHas('categories', function($q) use ($product) {
                $q->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // Calculate average rating from loaded reviews
        $avgRating = $product->reviews->avg('rating');
        $reviewsCount = $product->reviews->count();

        return view('products.show', compact('product', 'relatedProducts', 'avgRating', 'reviewsCount'));
    }
}
