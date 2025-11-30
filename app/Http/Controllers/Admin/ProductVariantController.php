<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()->paginate(20);
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
            $variant->delete();
            return redirect()->route('admin.products.variants.index', $product)
                ->with('success', 'Biến thể đã được xóa!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể xóa biến thể: ' . $e->getMessage());
        }
    }
}
