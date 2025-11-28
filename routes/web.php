<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController;

// --- ROUTE PUBLIC (KHÁCH HÀNG) ---

// Trang chủ: Hiển thị danh sách sản phẩm
Route::get('/', [HomeController::class, 'index'])->name('home');

// Chi tiết sản phẩm
Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');

// Giỏ hàng
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::get('add-to-cart/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::patch('update-cart', [CartController::class, 'update'])->name('cart.update');
Route::delete('remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');


// --- ROUTE NGƯỜI DÙNG (USER ĐÃ ĐĂNG NHẬP) ---
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard của User
    Route::get('/dashboard', function () {
        // Nếu là admin mà vào link này thì đẩy sang trang admin
        if(Auth::user()->role === 'admin'){
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard'); // View mặc định cho User
    })->name('dashboard');

    // Thanh toán
    Route::get('checkout', [OrderController::class, 'index'])->name('checkout.index');
    Route::post('checkout', [OrderController::class, 'store'])->name('checkout.store');

    // Quản lý hồ sơ cá nhân
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- ROUTE QUẢN TRỊ (ADMIN) ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // [QUAN TRỌNG] Đã sửa: Trỏ đúng về file view dashboard của admin
    // File view này nằm tại resources/views/layouts/dashboard.blade.php
    Route::get('/dashboard', function () { 
        return view('layouts.dashboard'); 
    })->name('dashboard');
    
    // Import & Export
    Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
    Route::post('products/import', [ProductController::class, 'import'])->name('products.import');

    // Quản lý Danh mục & Sản phẩm
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
});

require __DIR__.'/auth.php';