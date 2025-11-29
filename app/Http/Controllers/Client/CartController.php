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

        // --- TRƯỜNG HỢP 1: MUA NGAY ---
        if ($request->has('buy_now') && $request->buy_now == 'true') {
            $buyNowItem = [
                $id => [
                    "name" => $product->name,
                    "quantity" => $quantity,
                    "price" => $product->price,
                    "image" => $product->image
                ]
            ];
            session()->put('checkout_data', $buyNowItem);
            session()->put('checkout_type', 'buy_now');
            return redirect()->route('checkout.index');
        }

        // --- TRƯỜNG HỢP 2: GIỎ HÀNG ---
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

    // --- CHỨC NĂNG CẬP NHẬT SỐ LƯỢNG ---
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            // 1. Cập nhật Session
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);

            // 2. Cập nhật Database (Nếu đã login)
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())
                    ->where('product_id', $request->id)
                    ->update(['quantity' => $request->quantity]);
            }
            
            return redirect()->back()->with('success', 'Đã cập nhật giỏ hàng!');
        }
    }

    // --- CHỨC NĂNG XÓA SẢN PHẨM (BẠN CẦN ĐOẠN NÀY) ---
    public function remove(Request $request)
    {
        if($request->id) {
            // 1. Xóa khỏi Session
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }

            // 2. Xóa khỏi Database (Nếu đã login)
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())
                    ->where('product_id', $request->id)
                    ->delete();
            }

            return redirect()->back()->with('success', 'Đã xóa sách khỏi giỏ!');
        }
    }
}