<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category; // [Thêm] Import Model Category
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // [Thêm] Lấy danh sách danh mục để hiển thị ở Filter Sidebar
        $categories = Category::all();

        $query = Product::query();

        // 1. Tìm kiếm từ khóa (Giữ nguyên logic cũ)
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('author', 'LIKE', "%{$keyword}%");
            });
        }

        // 2. [Thêm] Lọc theo Danh mục
        if ($request->has('category') && $request->category != 'all') {
            $query->where('category_id', $request->category);
        }

        // 3. [Thêm] Lọc theo Giá
        if ($request->has('price_min') && $request->price_min != '') {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && $request->price_max != '') {
            $query->where('price', '<=', $request->price_max);
        }

        // 4. [Thêm] Sắp xếp (Option bổ sung)
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc': $query->orderBy('price', 'asc'); break;
                case 'price_desc': $query->orderBy('price', 'desc'); break;
                default: $query->latest(); break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(6);
        
        // Truyền thêm biến $categories sang view
        return view('welcome', compact('products', 'categories'));
    }
    
    public function show($id) {
    // 1. Eager load 'category' để lấy tên danh mục cho breadcrumb
    $product = Product::with('category')->findOrFail($id);
    
    // 2. Lấy 4 sản phẩm liên quan (cùng danh mục, trừ sản phẩm hiện tại)
    $relatedProducts = Product::where('category_id', $product->category_id)
                              ->where('id', '!=', $id)
                              ->inRandomOrder() // Lấy ngẫu nhiên
                              ->take(4)
                              ->get();

    return view('client.products.show', compact('product', 'relatedProducts'));
}
}