<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Xem giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('client.cart.index', compact('cart'));
    }

    // Thêm vào giỏ
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);

        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Số lượng sản phẩm không đủ!');
        }

        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

        //Nếu đã đăng nhập -> Lưu vào Database
        if (Auth::check()) {
            $dbCart = Cart::where('user_id', Auth::id())
                          ->where('product_id', $id)
                          ->first();
            
            if ($dbCart) {
                $dbCart->quantity = $cart[$id]['quantity'];
                $dbCart->save();
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $id,
                    'quantity' => $cart[$id]['quantity']
                ]);
            }
        }

        // [MỚI] Kiểm tra nếu có tham số 'buy_now' trên URL thì chuyển đến giỏ hàng
        if (request()->has('buy_now') && request()->buy_now == 'true') {
            return redirect()->route('checkout.index');
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

            //Cập nhật DB nếu đã đăng nhập
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())
                    ->where('product_id', $request->id)
                    ->update(['quantity' => $request->quantity]);
            }

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

            //Xóa khỏi DB nếu đã đăng nhập
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())
                    ->where('product_id', $request->id)
                    ->delete();
            }
            return redirect()->back()->with('success', 'Đã xóa sách khỏi giỏ!');
        }
    }
}
