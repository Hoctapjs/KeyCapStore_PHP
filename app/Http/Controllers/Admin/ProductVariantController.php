<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()->with('images')->paginate(20);
        return view('admin.products.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product)
    {
        return view('admin.products.variants.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:255|unique:product_variants,sku',
            'option_keys' => 'nullable|array',
            'option_values' => 'nullable|array',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Build option_values from keys and values
            $optionValues = null;
            if (!empty($validated['option_keys']) && !empty($validated['option_values'])) {
                $optionValues = [];
                foreach ($validated['option_keys'] as $index => $key) {
                    if (!empty($key) && isset($validated['option_values'][$index]) && !empty($validated['option_values'][$index])) {
                        $optionValues[$key] = $validated['option_values'][$index];
                    }
                }
                if (empty($optionValues)) {
                    $optionValues = null;
                }
            }

            $variant = new ProductVariant();
            $variant->product_id = $product->id;
            $variant->sku = $validated['sku'];
            $variant->option_values = $optionValues;
            $variant->price = $validated['price'];
            $variant->stock_quantity = $validated['stock_quantity'];
            $variant->save();

            // Upload variant images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images/products'), $filename);
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'variant_id' => $variant->id,
                        'image_url' => '/images/products/' . $filename,
                        'alt' => $product->title . ' - ' . $variant->sku,
                        'sort_order' => $index
                    ]);
                }
            }

            // Update product total stock
            $this->updateProductStock($product);

            DB::commit();
            return redirect()->route('admin.products.variants.index', $product)
                ->with('success', 'Biến thể đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Product $product, ProductVariant $variant)
    {
        // Ensure variant belongs to product
        if ($variant->product_id !== $product->id) {
            abort(404);
        }

        $variant->load('images');

        return view('admin.products.variants.edit', compact('product', 'variant'));
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        // Ensure variant belongs to product
        if ($variant->product_id !== $product->id) {
            abort(404);
        }

        $validated = $request->validate([
            'sku' => 'required|string|max:255|unique:product_variants,sku,' . $variant->id,
            'option_keys' => 'nullable|array',
            'option_values' => 'nullable|array',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Build option_values from keys and values
            $optionValues = null;
            if (!empty($validated['option_keys']) && !empty($validated['option_values'])) {
                $optionValues = [];
                foreach ($validated['option_keys'] as $index => $key) {
                    if (!empty($key) && isset($validated['option_values'][$index]) && !empty($validated['option_values'][$index])) {
                        $optionValues[$key] = $validated['option_values'][$index];
                    }
                }
                if (empty($optionValues)) {
                    $optionValues = null;
                }
            }

            $variant->sku = $validated['sku'];
            $variant->option_values = $optionValues;
            $variant->price = $validated['price'];
            $variant->stock_quantity = $validated['stock_quantity'];
            $variant->save();

            // Upload new variant images
            if ($request->hasFile('images')) {
                $maxSortOrder = $variant->images()->max('sort_order') ?? -1;
                foreach ($request->file('images') as $index => $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images/products'), $filename);
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'variant_id' => $variant->id,
                        'image_url' => '/images/products/' . $filename,
                        'alt' => $product->title . ' - ' . $variant->sku,
                        'sort_order' => $maxSortOrder + $index + 1
                    ]);
                }
            }

            // Update product total stock
            $this->updateProductStock($product);

            DB::commit();
            return redirect()->route('admin.products.variants.index', $product)
                ->with('success', 'Biến thể đã được cập nhật!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        // Ensure variant belongs to product
        if ($variant->product_id !== $product->id) {
            abort(404);
        }

        try {
            // Delete variant images
            foreach ($variant->images as $image) {
                $imagePath = public_path($image->image_url);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $image->delete();
            }
            
            $variant->delete();

            // Update product total stock
            $this->updateProductStock($product);

            return redirect()->route('admin.products.variants.index', $product)
                ->with('success', 'Biến thể đã được xóa!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể xóa biến thể: ' . $e->getMessage());
        }
    }

    /**
     * Update product stock based on sum of all variants stock
     */
    private function updateProductStock(Product $product)
    {
        $totalStock = $product->variants()->sum('stock_quantity');
        $product->stock = $totalStock;
        $product->save();
    }
}
