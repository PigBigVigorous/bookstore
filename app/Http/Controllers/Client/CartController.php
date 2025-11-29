<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Xem giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('client.cart.index', compact('cart'));
    }

    // Thêm vào giỏ
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

        // [MỚI] Kiểm tra nếu có tham số 'buy_now' trên URL thì chuyển đến giỏ hàng
        if (request()->has('buy_now') && request()->buy_now == 'true') {
            return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ, vui lòng kiểm tra!');
        }

        return redirect()->back()->with('success', 'Đã thêm sách vào giỏ!');
    }

    // Cập nhật số lượng
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Đã cập nhật giỏ hàng!');
        }
    }

    // Xóa khỏi giỏ
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Đã xóa sách khỏi giỏ!');
        }
    }
}
