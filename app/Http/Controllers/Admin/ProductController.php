<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'categories', 'productImages', 'variants']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(20);
        $brands = Brand::all();

        return view('admin.products.index', compact('products', 'brands'));
    }

    public function create()
    {
        $brands = Brand::all();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $tags = ProductTag::all();

        return view('admin.products.create', compact('brands', 'categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,archived',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:product_tags,id',
        ]);

        DB::beginTransaction();
        try {
            $product = new Product();
            $product->title = $validated['title'];
            $product->code = $validated['code'] ?? null;
            $product->slug = Str::slug($validated['title']);
            $product->brand_id = $validated['brand_id'] ?? null;
            $product->price = $validated['price'];
            $product->stock = $validated['stock'];
            $product->description = $validated['description'] ?? null;
            $product->status = $validated['status'];
            $product->save();

            if (!empty($validated['categories'])) {
                $categoryData = [];
                foreach ($validated['categories'] as $index => $categoryId) {
                    $categoryData[$categoryId] = ['primary_flag' => $index === 0];
                }
                $product->categories()->attach($categoryData);
            }

            if (!empty($validated['tags'])) {
                $product->tags()->attach($validated['tags']);
            }

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'categories', 'tags', 'variants', 'productImages', 'reviews']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $brands = Brand::all();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $tags = ProductTag::all();
        $product->load(['categories', 'tags']);

        return view('admin.products.edit', compact('product', 'brands', 'categories', 'tags'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,archived',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:product_tags,id',
        ]);

        DB::beginTransaction();
        try {
            $product->title = $validated['title'];
            $product->code = $validated['code'] ?? null;
            $product->slug = Str::slug($validated['title']);
            $product->brand_id = $validated['brand_id'] ?? null;
            $product->price = $validated['price'];
            $product->stock = $validated['stock'];
            $product->description = $validated['description'] ?? null;
            $product->status = $validated['status'];
            $product->save();

            if (!empty($validated['categories'])) {
                $categoryData = [];
                foreach ($validated['categories'] as $index => $categoryId) {
                    $categoryData[$categoryId] = ['primary_flag' => $index === 0];
                }
                $product->categories()->sync($categoryData);
            } else {
                $product->categories()->detach();
            }

            if (!empty($validated['tags'])) {
                $product->tags()->sync($validated['tags']);
            } else {
                $product->tags()->detach();
            }

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được cập nhật!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được xóa!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể xóa sản phẩm: ' . $e->getMessage());
        }
    }
}
