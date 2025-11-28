<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Xem danh sách đơn hàng
    public function index()
    {
        // Lấy danh sách đơn hàng, sắp xếp mới nhất lên đầu, phân trang
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    // Xem chi tiết đơn hàng
    public function show(Order $order)
    {
        // Load thêm thông tin chi tiết đơn hàng và sản phẩm
        $order->load('details.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,shipping,completed,canceled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
    
    // Xóa đơn hàng (nếu cần, thường ít khi xóa đơn hàng thực tế)
    public function destroy(Order $order)
    {
        $order->details()->delete(); // Xóa chi tiết trước
        $order->delete(); // Xóa đơn hàng
        return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng!');
    }
}