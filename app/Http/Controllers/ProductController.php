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
                },
                'variants:id,product_id,price,stock_quantity'
            ])
            ->withAvg(['reviews as reviews_avg_rating' => function($query) {
                $query->where('status', 'approved');
            }], 'rating')
            ->where('status', 'active');

        // Filter by category
        if ($request->filled('category')) {
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

        // Filter by price range (using variants price)
        if ($request->filled('min_price') && is_numeric($request->min_price)) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }
        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // Filter by rating (products with avg rating >= selected value)
        if ($request->filled('rating') && is_numeric($request->rating)) {
            $minRating = (int) $request->rating;
            $query->having('reviews_avg_rating', '>=', $minRating);
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
                // Sort by minimum variant price ascending
                $query->withMin('variants', 'price')
                      ->orderBy('variants_min_price', 'asc');
                break;
            case 'price_desc':
                // Sort by minimum variant price descending
                $query->withMin('variants', 'price')
                      ->orderBy('variants_min_price', 'desc');
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

        // Get filter data - only show categories and brands with products
        $brands = Brand::select('brands.id', 'brands.name', 'brands.slug')
            ->join('products', 'brands.id', '=', 'products.brand_id')
            ->where('products.status', 'active')
            ->groupBy('brands.id', 'brands.name', 'brands.slug')
            ->orderBy('brands.name')
            ->get();
        
        $categories = Category::select('id', 'name', 'slug', 'parent_id')
            ->whereNull('parent_id')
            ->whereHas('products', function($query) {
                $query->where('status', 'active');
            })
            ->orWhereHas('children', function($query) {
                $query->whereHas('products', function($q) {
                    $q->where('status', 'active');
                });
            })
            ->with(['children' => function($query) {
                $query->select('id', 'name', 'slug', 'parent_id')
                    ->whereHas('products', function($q) {
                        $q->where('status', 'active');
                    });
            }])
            ->get();
        
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
                'variants.images',
                'productImages' => function($query) {
                    $query->select('id', 'product_id', 'variant_id', 'image_url', 'alt', 'sort_order')
                        ->orderBy('sort_order');
                }
            ])
            ->active()
            ->firstOrFail();

        // Get related products (same category) - optimized
        $relatedProducts = Product::select('id', 'title', 'slug', 'price', 'stock', 'brand_id')
            ->with([
                'brand:id,name',
                'productImages' => function($query) {
                    $query->select('id', 'product_id', 'image_url')->orderBy('sort_order')->limit(1);
                },
                'variants:id,product_id,price,stock_quantity'
            ])
            ->active()
            ->whereHas('categories', function($q) use ($product) {
                $q->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // Calculate reviews statistics
        $allReviews = $product->reviews()->where('status', 'approved')->get();
        $avgRating = $allReviews->avg('rating') ?? 0;
        $totalReviews = $allReviews->count();
        
        // Rating statistics (count for each star)
        $ratingStats = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingStats[$i] = $allReviews->where('rating', $i)->count();
        }

        // Get reviews with pagination and optional filter by rating
        $reviewsQuery = $product->reviews()
            ->with('user:id,name')
            ->where('status', 'approved');
        
        // Filter by rating if provided
        if (request('rating') && request('rating') != 'all') {
            $reviewsQuery->where('rating', request('rating'));
        }
        
        $reviews = $reviewsQuery->latest()->paginate(10);

        // Check if current user has reviewed this product
        $userReview = null;
        if (auth()->check()) {
            $userReview = $product->reviews()
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('products.show', compact(
            'product', 
            'relatedProducts', 
            'avgRating', 
            'totalReviews',
            'ratingStats',
            'reviews',
            'userReview'
        ));
    }
}
