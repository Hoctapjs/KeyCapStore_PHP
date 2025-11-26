@php
// Chỉ tính nếu chưa có sẵn (controller truyền vào rồi thì thôi)
if (!isset($cart) || !isset($cartCount) || !isset($cartTotal)) {
if (\Illuminate\Support\Facades\Auth::check()) {
$cart = \App\Models\Cart::firstOrCreate(
['user_id' => \Illuminate\Support\Facades\Auth::id()]
)->load(['items.product', 'items.variant']);
} else {
$sessionId = session()->getId();
$cart = \App\Models\Cart::firstOrCreate(
['session_id' => $sessionId]
)->load(['items.product', 'items.variant']);
}

$cartCount = $cart->items->sum('quantity');
$cartTotal = $cart->items->sum(function ($item) {
return $item->quantity * $item->price_snapshot;
});
}
@endphp