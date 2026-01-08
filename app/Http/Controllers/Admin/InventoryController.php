<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        // Hiển thị danh sách sản phẩm với tổng tồn kho
        $query = Product::with(['variants', 'brand', 'productImages']);

        // Search by product title or code
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $products = $query->withCount('variants')
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);

        // Statistics từ tất cả variants
        $stats = [
            'sufficient' => ProductVariant::where('stock_quantity', '>', 10)->count(),
            'low' => ProductVariant::where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10)->count(),
            'out' => ProductVariant::where('stock_quantity', '=', 0)->count(),
            'total_value' => ProductVariant::selectRaw('SUM(stock_quantity * price) as total')->value('total') ?? 0,
        ];

        return view('admin.inventory.index', compact('products', 'stats'));
    }

    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'movement_type' => 'required|in:purchase,sale,adjustment,return,manual',
            'unit_cost' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $variant = ProductVariant::findOrFail($validated['variant_id']);
            
            $changeQty = $validated['type'] == 'in' ? $validated['quantity'] : -$validated['quantity'];
            $variant->stock_quantity += $changeQty;
            
            if ($variant->stock_quantity < 0) {
                throw new \Exception('Số lượng tồn kho không được âm');
            }
            
            $variant->save();

            // Create inventory movement
            InventoryMovement::create([
                'product_id' => $variant->product_id,
                'variant_id' => $variant->id,
                'change_qty' => $changeQty,
                'type' => $validated['movement_type'],
                'unit_cost' => $validated['unit_cost'] ?? null,
                'note' => $validated['note'] ?? null,
            ]);

            DB::commit();
            return back()->with('success', 'Đã điều chỉnh tồn kho thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        $product->load(['variants', 'inventoryMovements' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(50);
        }]);

        return view('admin.inventory.show', compact('product'));
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'change_qty' => 'required|integer',
            'change_qty_type' => 'required|in:in,out',
            'type' => 'required|in:purchase,sale,adjustment,return,manual',
            'unit_cost' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Xử lý loại điều chỉnh
            $change = $validated['change_qty_type'] == 'in' ? $validated['change_qty'] : -$validated['change_qty'];
            
            // Update product stock
            $product->stock += $change;
            
            if ($product->stock < 0) {
                throw new \Exception('Số lượng tồn kho không được âm');
            }
            
            $product->save();

            // Create inventory movement
            InventoryMovement::create([
                'product_id' => $product->id,
                'variant_id' => null,
                'change_qty' => $change,
                'type' => $validated['type'],
                'unit_cost' => $validated['unit_cost'] ?? null,
                'note' => $validated['note'] ?? null,
            ]);

            DB::commit();
            return back()->with('success', 'Tồn kho đã được cập nhật!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function updateVariantStock(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'change_qty' => 'required|integer',
            'type' => 'required|in:purchase,sale,adjustment,return,manual',
            'unit_cost' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Update variant stock
            $variant->stock_quantity += $validated['change_qty'];
            $variant->save();

            // Create inventory movement
            InventoryMovement::create([
                'product_id' => $variant->product_id,
                'variant_id' => $variant->id,
                'change_qty' => $validated['change_qty'],
                'type' => $validated['type'],
                'unit_cost' => $validated['unit_cost'] ?? null,
                'note' => $validated['note'] ?? null,
            ]);

            DB::commit();
            return back()->with('success', 'Tồn kho biến thể đã được cập nhật!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
