<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Cart;
use App\Models\Product;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        
        $user = Auth::user();
        
        $sessionCart = session()->get('cart', []);

        foreach ($sessionCart as $id => $details) {
            $existCart = Cart::where('user_id', $user->id)->where('product_id', $id)->first();
            if ($existCart) {
                // Nếu sách đã có trong DB, cộng thêm số lượng
                $existCart->quantity += $details['quantity'];
                $existCart->save();
            } else {
                // Nếu chưa có, tạo mới
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity']
                ]);
            }
        }

        $dbCarts = Cart::with('product')->where('user_id', $user->id)->get();
        $finalCart = [];

        foreach ($dbCarts as $c) {
            if($c->product) { // Kiểm tra sản phẩm còn tồn tại không
                $finalCart[$c->product_id] = [
                    "name" => $c->product->name,
                    "quantity" => $c->quantity,
                    "price" => $c->product->price,
                    "image" => $c->product->image
                ];
            }
        }

        session()->put('cart', $finalCart);

        if ($user->role === 'user') {
            return redirect('/'); 
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
