<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    // Nếu đã đăng nhập VÀ role là admin
    if (Auth::check() && Auth::user()->role == 'admin') {
        return $next($request);
    }

    // Nếu không phải admin, đẩy về trang chủ hoặc báo lỗi
    return redirect('/')->with('error', 'Bạn không có quyền truy cập Admin!');
    }
}
