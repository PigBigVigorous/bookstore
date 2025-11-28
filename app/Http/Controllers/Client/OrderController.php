<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced; // Chúng ta sẽ tạo mail này ở bước sau

class OrderController extends Controller
{
    // Hiển thị form thanh toán
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán!');
        }
        $cart = session()->get('cart');
        if (!$cart) {
            return redirect()->route('home');
        }
        return view('client.checkout.index', compact('cart'));
    }

    // Xử lý đặt hàng
    public function store(Request $request)
    {
        $cart = session()->get('cart');
        $user = Auth::user();
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        DB::beginTransaction(); // Đảm bảo dữ liệu toàn vẹn
        try {
            // 1. Tạo đơn hàng
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $total,
                'status' => 'pending',
                'payment_method' => 'cod'
            ]);

            // 2. Tạo chi tiết đơn hàng
            foreach($cart as $id => $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            // 3. Gửi Email (Bỏ comment sau khi làm Bước 3)
            // Mail::to($user->email)->send(new OrderPlaced($order));

            // 4. Xóa giỏ hàng
            session()->forget('cart');
            
            DB::commit();
            return redirect()->route('home')->with('success', 'Đặt hàng thành công! Kiểm tra email của bạn.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }
}
