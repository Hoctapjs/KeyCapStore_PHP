<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Get search suggestions based on query
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        $categorySlug = $request->get('category', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'categories' => [],
                'brands' => [],
                'products' => []
            ]);
        }

        // Get selected category if any
        $selectedCategory = null;
        if (!empty($categorySlug)) {
            $selectedCategory = Category::where('slug', $categorySlug)->first();
        }

        // Search categories - only if no category filter selected
        $categories = collect();
        if (empty($categorySlug)) {
            $categories = Category::where('name', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get(['id', 'name', 'slug']);
        }

        // Search brands - filter by category if selected
        $brandsQuery = Brand::where('name', 'LIKE', "%{$query}%");
        
        if ($selectedCategory) {
            // Only show brands that have products in this category
            $brandsQuery->whereHas('products', function($q) use ($selectedCategory) {
                $q->where('status', 'active')
                  ->whereHas('categories', function($cq) use ($selectedCategory) {
                      $cq->where('categories.id', $selectedCategory->id);
                  });
            });
        }
        
        $brands = $brandsQuery->limit(5)->get(['id', 'name', 'slug']);

        // Get brand IDs that match the query (to include their products)
        $matchingBrandIds = $brands->pluck('id')->toArray();

        // Search products - by title/description OR by matching brand
        $productsQuery = Product::where('status', 'active')
            ->where(function($q) use ($query, $matchingBrandIds) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
                
                // Also include products from matching brands
                if (!empty($matchingBrandIds)) {
                    $q->orWhereIn('brand_id', $matchingBrandIds);
                }
            });
        
        // Filter by category if selected
        if ($selectedCategory) {
            $productsQuery->whereHas('categories', function($q) use ($selectedCategory) {
                $q->where('categories.id', $selectedCategory->id);
            });
        }
        
        $products = $productsQuery
            ->with(['productImages' => function($q) {
                $q->orderBy('sort_order')->limit(1);
            }, 'brand'])
            ->limit(8)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'slug' => $product->slug,
                    'price' => $product->price_range,
                    'brand' => $product->brand?->name,
                    'image' => $product->productImages->first()?->image_url ?? asset('images/placeholder.svg')
                ];
            });

        return response()->json([
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products
        ]);
    }

    /**
     * Get products by brand
     */
    public function productsByBrand(Request $request, $brandSlug)
    {
        $brand = Brand::where('slug', $brandSlug)->firstOrFail();
        
        $products = Product::where('status', 'active')
            ->where('brand_id', $brand->id)
            ->with(['productImages' => function($q) {
                $q->orderBy('sort_order')->limit(1);
            }])
            ->limit(6)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'slug' => $product->slug,
                    'price' => $product->price_range,
                    'image' => $product->productImages->first()?->image_url ?? asset('images/placeholder.svg')
                ];
            });

        return response()->json([
            'brand' => $brand,
            'products' => $products
        ]);
    }

    /**
     * Get products by category
     */
    public function productsByCategory(Request $request, $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        
        $products = Product::where('status', 'active')
            ->whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category->id);
            })
            ->with(['productImages' => function($q) {
                $q->orderBy('sort_order')->limit(1);
            }])
            ->limit(6)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'slug' => $product->slug,
                    'price' => $product->price_range,
                    'image' => $product->productImages->first()?->image_url ?? asset('images/placeholder.svg')
                ];
            });

        return response()->json([
            'category' => $category,
            'products' => $products
        ]);
    }
}
