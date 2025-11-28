<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Thống kê số lượng
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'user')->count();
        
        // 2. Tính tổng doanh thu (chỉ tính đơn không bị hủy)
        $totalRevenue = Order::where('status', '!=', 'canceled')->sum('total_price');

        // 3. Lấy 5 đơn hàng mới nhất
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalOrders', 
            'totalCustomers', 
            'totalRevenue',
            'recentOrders'
        ));
    }
}