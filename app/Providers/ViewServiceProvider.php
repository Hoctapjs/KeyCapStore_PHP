<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Chia sẻ cart cho mọi view
        View::composer('*', function ($view) {

            // Lấy đúng cart theo user hoặc guest
            if (Auth::check()) {
                $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            } else {
                $cart = Cart::firstOrCreate(['session_id' => session()->getId()]);
            }

            $cart->load('items.product');

            $cartCount = $cart->items->sum('quantity');

            $cartTotal = $cart->items->sum(function ($item) {
                return $item->quantity * $item->price_snapshot;
            });

            $view->with([
                'cart' => $cart,
                'cartCount' => $cartCount,
                'cartTotal' => $cartTotal,
            ]);
        });
    }
}
