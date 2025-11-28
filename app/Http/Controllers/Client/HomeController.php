<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Tìm kiếm
        if ($request->has('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('author', 'LIKE', "%{$keyword}%");
        }

        $products = $query->paginate(12);
        return view('welcome', compact('products'));
    }
    
    public function show($id) {
        $product = Product::findOrFail($id);
        return view('client.products.show', compact('product'));
    }
}
