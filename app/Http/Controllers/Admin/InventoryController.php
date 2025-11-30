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
        // Hiển thị danh sách variants để quản lý tồn kho
        $query = ProductVariant::with(['product.brand', 'product.productImages']);

        // Filter by stock status
        if ($request->has('stock_status') && $request->stock_status !== '') {
            $status = $request->stock_status;
            if ($status == 'out') {
                $query->where('stock_quantity', '=', 0);
            } elseif ($status == 'low') {
                $query->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10);
            } elseif ($status == 'sufficient') {
                $query->where('stock_quantity', '>', 10);
            }
        }

        // Search by product title or variant SKU
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('title', 'like', "%{$search}%")
                                   ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        $variants = $query->orderBy('stock_quantity', 'asc')->paginate(20);

        // Statistics từ variants
        $stats = [
            'sufficient' => ProductVariant::where('stock_quantity', '>', 10)->count(),
            'low' => ProductVariant::where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10)->count(),
            'out' => ProductVariant::where('stock_quantity', '=', 0)->count(),
            'total_value' => ProductVariant::selectRaw('SUM(stock_quantity * price) as total')->value('total') ?? 0,
        ];

        return view('admin.inventory.index', compact('variants', 'stats'));
    }

    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
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
                'reason' => 'manual',
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
            'reason' => 'required|in:order,restock,manual,refund',
            'note' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Update product stock
            $product->stock += $validated['change_qty'];
            $product->save();

            // Create inventory movement
            InventoryMovement::create([
                'product_id' => $product->id,
                'variant_id' => null,
                'change_qty' => $validated['change_qty'],
                'reason' => $validated['reason'],
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
            'reason' => 'required|in:order,restock,manual,refund',
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
                'reason' => $validated['reason'],
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
