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
        $query = Product::with(['brand', 'productImages']);

        // Filter by stock status
        if ($request->has('stock_status') && $request->stock_status !== '') {
            $status = $request->stock_status;
            if ($status == 'out') {
                $query->where('stock', '=', 0);
            } elseif ($status == 'low') {
                $query->where('stock', '>', 0)->where('stock', '<=', 10);
            } elseif ($status == 'sufficient') {
                $query->where('stock', '>', 10);
            }
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('stock', 'asc')->paginate(20);

        // Statistics
        $stats = [
            'sufficient' => Product::where('stock', '>', 10)->count(),
            'low' => Product::where('stock', '>', 0)->where('stock', '<=', 10)->count(),
            'out' => Product::where('stock', '=', 0)->count(),
            'total_value' => Product::selectRaw('SUM(stock * price) as total')->value('total') ?? 0,
        ];

        return view('admin.inventory.index', compact('products', 'stats'));
    }

    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($validated['product_id']);
            
            $changeQty = $validated['type'] == 'in' ? $validated['quantity'] : -$validated['quantity'];
            $product->stock += $changeQty;
            
            if ($product->stock < 0) {
                throw new \Exception('Số lượng tồn kho không được âm');
            }
            
            $product->save();

            // Create inventory movement
            InventoryMovement::create([
                'product_id' => $product->id,
                'variant_id' => null,
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
