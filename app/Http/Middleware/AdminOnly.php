<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     * Chỉ cho phép Admin truy cập, Staff bị từ chối.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login.form');
        }

        $userRole = Auth::user()->role;

        // Chỉ Admin mới được truy cập
        if ($userRole === 'admin') {
            return $next($request);
        }

        return abort(403, 'Chức năng này chỉ dành cho Admin.');
    }
}
