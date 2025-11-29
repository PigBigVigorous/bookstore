<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class OrderController extends Controller
{
    // Hiển thị form thanh toán
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán!');
        }
        $cart = session()->get('cart', []);
        if(empty($cart)) {
            return redirect()->route('home')->with('error', 'Giỏ hàng trống!');
        }
        $user = Auth::user();
        return view('client.checkout.index', compact('cart', 'user'));
    }

    // Xử lý đặt hàng
    public function store(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required',
            'receiver_phone' => 'required',
            'receiver_address' => 'required',
        ]);

        $cart = session()->get('cart', []);
        
        // Tính tổng tiền
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        try {
            DB::beginTransaction();

            // 1. Tạo đơn hàng và LIÊN KẾT VỚI USER
            $order = Order::create([
                'user_id' => Auth::id(), // [QUAN TRỌNG] Lấy ID người dùng hiện tại
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'receiver_address' => $request->receiver_address,
                'note' => $request->note,
                'total_price' => $total,
                'status' => 'pending'
            ]);

            // 2. Lưu chi tiết đơn hàng
            foreach($cart as $id => $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
                
                // Trừ tồn kho (Optional)
                $product = Product::find($id);
                if($product) {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            DB::commit();

            // 3. Xóa giỏ hàng sau khi mua thành công
            session()->forget('cart');

            return redirect()->route('home')->with('success', 'Đặt hàng thành công! Mã đơn: #' . $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
