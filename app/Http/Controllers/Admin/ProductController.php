<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->orderBy('id', 'desc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Thêm sách thành công!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate(['name' => 'required', 'price' => 'required|numeric']);
        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sách thành công!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sách!');
    }

    // --- EXPORT EXCEL ---
    public function export() 
    {
        return Excel::download(new ProductsExport, 'danh-sach-sach.xlsx');
    }
   
    // --- IMPORT EXCEL ---
    public function import(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // Chạy lệnh import
            Excel::import(new ProductsImport, $request->file('file'));
            
            return redirect()->back()->with('success', 'Import dữ liệu thành công! Kiểm tra danh sách bên dưới.');
        } catch ( ValidationException $e) {
             $failures = $e->failures();
             return redirect()->back()->with('error', 'Lỗi dữ liệu trong file Excel.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi Import: ' . $e->getMessage());
        }
    }
}