<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Xem danh sách đơn hàng
    public function index()
    {
        // Lấy danh sách đơn hàng, sắp xếp mới nhất lên đầu, phân trang
        $orders = Order::with('user')->orderBy('id', 'asc')->paginate(10); 
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
        // 1. Tắt kiểm tra khóa ngoại TRƯỚC KHI bắt đầu transaction để tránh lỗi ràng buộc
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            DB::beginTransaction();

            $deletedId = $order->id; // Lưu lại ID của đơn bị xóa

            // 2. Xóa chi tiết và đơn hàng
            $order->details()->delete();
            $order->delete();

            // 3. Cập nhật lại ID cho các đơn hàng phía sau (lấp chỗ trống)
            $ordersToUpdate = Order::where('id', '>', $deletedId)
                                   ->orderBy('id', 'asc')
                                   ->get();

            foreach ($ordersToUpdate as $item) {
                $oldId = $item->id;
                $newId = $oldId - 1; // Giảm ID xuống 1 đơn vị

                // Cập nhật bảng orders
                DB::table('orders')->where('id', $oldId)->update(['id' => $newId]);
                
                // Cập nhật bảng order_details
                DB::table('order_details')->where('order_id', $oldId)->update(['order_id' => $newId]);
            }

            // [QUAN TRỌNG] Commit transaction TẠI ĐÂY (trước khi chạy ALTER TABLE)
            // Vì ALTER TABLE sẽ tự động commit, nên ta phải chốt dữ liệu trước.
            DB::commit(); 

            // 4. Reset lại bộ đếm Auto Increment
            $maxId = DB::table('orders')->max('id');
            $nextId = $maxId ? $maxId + 1 : 1;
            
            // Các lệnh này chạy độc lập sau khi transaction đã hoàn tất
            DB::statement("ALTER TABLE orders AUTO_INCREMENT = $nextId");
            DB::statement("ALTER TABLE order_details AUTO_INCREMENT = 1");

            // Bật lại kiểm tra khóa ngoại
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng và cập nhật lại thứ tự mã!');

        } catch (\Exception $e) {
            // Kiểm tra xem transaction có đang active không trước khi rollback
            if(DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1'); // Đảm bảo bật lại nếu lỗi
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}