<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function getCart()
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate([
                'user_id' => Auth::id(),
            ]);
        } else {
            $sessionId = session()->getId();
            $cart = Cart::firstOrCreate([
                'session_id' => $sessionId,
            ]);
        }

        return $cart->load(['items.product', 'items.variant']);
    }

    public function index()
    {
        $cart = $this->getCart();

        $subtotal = $cart->items->sum(function ($item) {
            return $item->price_snapshot * $item->quantity;
        });

        return view('cart.index', compact('cart', 'subtotal'));
    }

    /** Add to cart */
    // public function add(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //         'variant_id' => 'nullable|exists:product_variants,id',
    //         'quantity' => 'required|integer|min:1'
    //     ]);

    //     $cart = $this->getCart();
    //     $product = Product::findOrFail($request->product_id);

    //     $cartItem = $cart->items()
    //         ->where('product_id', $request->product_id)
    //         ->where('variant_id', $request->variant_id)
    //         ->first();

    //     if ($cartItem) {
    //         $cartItem->quantity += $request->quantity;
    //         $cartItem->save();
    //     } else {
    //         $cart->items()->create([
    //             'product_id' => $product->id,
    //             'variant_id' => $request->variant_id,
    //             'quantity' => $request->quantity,
    //             'price_snapshot' => $product->price
    //         ]);
    //     }

    //     return response()->json(['success' => true]);
    // }
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        $cart    = $this->getCart();
        $product = Product::findOrFail($request->product_id);

        // Chuẩn hóa variant_id: "" => null
        $variantId = $request->input('variant_id');
        if ($variantId === '' || $variantId === null) {
            $variantId = null;
        }

        $variant = null;
        if ($variantId) {
            // Đảm bảo variant thuộc về đúng product
            $variant = ProductVariant::where('product_id', $product->id)
                ->findOrFail($variantId);
        }

        // Xác định giá & tồn kho theo variant / product
        $price = $variant && $variant->price !== null
            ? $variant->price
            : $product->price;

        $availableStock = $variant
            ? $variant->stock_quantity
            : $product->stock;

        $qty = (int) $request->quantity;

        // Kiểm tra tổng quantity trong cart không vượt tồn kho
        $existingItem = $cart->items()
            ->where('product_id', $product->id)
            ->where('variant_id', $variantId)
            ->first();

        $currentQtyInCart = $existingItem ? $existingItem->quantity : 0;

        if ($qty + $currentQtyInCart > $availableStock) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng vượt quá tồn kho hiện có.'
            ], 422);
        }

        // Tạo / cập nhật cart item
        if ($existingItem) {
            $existingItem->quantity += $qty;
            // Có thể giữ lại price_snapshot cũ, hoặc cập nhật theo giá mới:
            $existingItem->price_snapshot = $price;
            $existingItem->save();
        } else {
            $cart->items()->create([
                'product_id'     => $product->id,
                'variant_id'     => $variantId,
                'quantity'       => $qty,
                'price_snapshot' => $price,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /** Update quantity */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getCart();
        $item = $cart->items()->findOrFail($itemId);

        $item->update([
            'quantity' => $request->quantity
        ]);

        return response()->json(['success' => true]);
    }

    /** Remove item */
    public function remove($itemId)
    {
        $cart = $this->getCart();

        $item = $cart->items()->findOrFail($itemId);
        $item->delete();

        return response()->json(['success' => true]);
    }

    /** Ajax Cart Info (header + offcanvas) */
    public function ajaxCartInfo()
    {
        $cart = $this->getCart();

        $cartCount = $cart->items->sum('quantity');
        $cartTotal = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price_snapshot;
        });

        $items = $cart->items->map(function ($item) {
            return [
                'title'   => $item->product->title,
                'variant' => $item->variant?->name,
                'quantity' => $item->quantity,
                'total'   => number_format($item->price_snapshot * $item->quantity, 0, ',', '.') . 'đ'
            ];
        });

        return response()->json([
            'cartCount' => $cartCount,
            'cartTotal' => number_format($cartTotal, 0, ',', '.') . 'đ',
            'items' => $items
        ]);
    }

    public function ajaxTable()
    {
        $cart = $this->getCart();
        $subtotal = $cart->items->sum(fn($i) => $i->price_snapshot * $i->quantity);

        return view('cart._table', compact('cart', 'subtotal'))->render();
    }
}
