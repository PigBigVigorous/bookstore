<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard của Admin (Phải có quyền admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); // Ngày mai sẽ tạo view admin riêng
    })->name('dashboard');
    
    // Mai thêm route quản lý sách ở đây
});

// Dashboard của User (Đăng nhập rồi mới vào)
Route::get('/dashboard', function () {
    // Logic check: Nếu là admin mà lỡ vào link này thì đẩy sang admin dashboard
    if(Auth::user()->role === 'admin'){
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard'); 
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Route trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');

// Route Admin (Sửa lại nhóm này)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    
    // Resource Route cho Category và Product
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
});

Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::get('add-to-cart/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::patch('update-cart', [CartController::class, 'update'])->name('cart.update');
Route::delete('remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');

Route::middleware(['auth'])->group(function () {
    Route::get('checkout', [OrderController::class, 'index'])->name('checkout.index');
    Route::post('checkout', [OrderController::class, 'store'])->name('checkout.store');
});

require __DIR__.'/auth.php';
