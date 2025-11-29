<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Cart;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Nếu người dùng bấm "Thanh toán" từ trang Giỏ hàng -> Reset lại chế độ Mua ngay
        if ($request->has('source') && $request->source == 'cart') {
            session()->forget(['checkout_data', 'checkout_type']);
        }

        // Kiểm tra xem đang checkout kiểu gì
        if (session('checkout_type') === 'buy_now') {
            $cart = session('checkout_data', []); // Lấy từ session tạm
        } else {
            $cart = session('cart', []); // Lấy từ giỏ hàng chính
        }

        if(empty($cart)) {
            return redirect()->route('home')->with('error', 'Không có sản phẩm để thanh toán!');
        }
        
        $user = Auth::user();
        return view('client.checkout.index', compact('cart', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required',
            'receiver_phone' => 'required',
            'receiver_address' => 'required',
        ]);

        // Xác định nguồn dữ liệu để tạo đơn
        if (session('checkout_type') === 'buy_now') {
            $cart = session('checkout_data', []);
        } else {
            $cart = session('cart', []);
        }

        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Dữ liệu đơn hàng lỗi!');
        }
        
        // Tính tổng tiền
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        try {
            DB::beginTransaction();

            // 1. Tạo đơn hàng
            $order = Order::create([
                'user_id' => Auth::id(),
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'receiver_address' => $request->receiver_address,
                'note' => $request->note,
                'total_price' => $total,
                'status' => 'pending'
            ]);

            // 2. Tạo chi tiết đơn hàng & Trừ tồn kho
            foreach($cart as $id => $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
                
                $product = Product::find($id);
                if($product) {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            DB::commit();

            // 3. DỌN DẸP DỮ LIỆU SAU KHI MUA
            if (session('checkout_type') === 'buy_now') {
                // Nếu là Mua ngay: Chỉ xóa session tạm, Giỏ hàng chính vẫn CÒN NGUYÊN
                session()->forget(['checkout_data', 'checkout_type']);
            } else {
                // Nếu là Mua từ giỏ: Xóa sạch giỏ hàng
                session()->forget('cart');
                if (Auth::check()) {
                    Cart::where('user_id', Auth::id())->delete();
                }
            }

            return redirect()->route('home')->with('success', 'Đặt hàng thành công! Mã đơn: #' . $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    // Hàm Hủy thanh toán
    public function cancel()
    {
        if (session('checkout_type') === 'buy_now') {
            // Nếu đang mua ngay mà hủy -> Xóa session tạm
            session()->forget(['checkout_data', 'checkout_type']);
            // Quay về trang chủ (hoặc trang sản phẩm nếu muốn)
            return redirect()->route('home');
        }

        // Nếu đang thanh toán giỏ hàng mà hủy -> Quay về trang giỏ hàng (dữ liệu vẫn còn)
        return redirect()->route('cart.index');
    }
}