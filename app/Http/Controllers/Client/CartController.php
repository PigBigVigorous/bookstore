<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('client.cart.index', compact('cart'));
    }

    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = (int) $request->input('quantity', 1);
        
        if ($quantity < 1) $quantity = 1;

        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Sản phẩm không đủ số lượng!');
        }

        // --- TRƯỜNG HỢP 1: MUA NGAY (Tách biệt hoàn toàn) ---
        if ($request->has('buy_now') && $request->buy_now == 'true') {
            // Tạo dữ liệu giỏ hàng tạm thời chỉ chứa 1 sản phẩm này
            $buyNowItem = [
                $id => [
                    "name" => $product->name,
                    "quantity" => $quantity,
                    "price" => $product->price,
                    "image" => $product->image
                ]
            ];

            // Lưu vào Session riêng, KHÔNG lưu vào session 'cart' chính
            session()->put('checkout_data', $buyNowItem);
            session()->put('checkout_type', 'buy_now'); // Đánh dấu loại thanh toán

            return redirect()->route('checkout.index');
        }

        // --- TRƯỜNG HỢP 2: THÊM VÀO GIỎ HÀNG (Logic cũ) ---
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

        // Lưu vào DB nếu đã đăng nhập
        if (Auth::check()) {
            $dbCart = Cart::where('user_id', Auth::id())
                          ->where('product_id', $id)
                          ->first();
            
            if ($dbCart) {
                $dbCart->quantity += $quantity;
                $dbCart->save();
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $id,
                    'quantity' => $quantity
                ]);
            }
        }

        return redirect()->back()->with('success', 'Đã thêm ' . $quantity . ' cuốn vào giỏ hàng!');
    }

    // ... (Các hàm update, remove giữ nguyên) ...
    public function update(Request $request) { /* ... */ }
    public function remove(Request $request) { /* ... */ }
}