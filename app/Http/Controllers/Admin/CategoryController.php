<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);
        
        Category::create($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    // --- SỬA CHỨC NĂNG EDIT ---
    public function edit(Category $category)
    {
        // Laravel tự động tìm category theo ID trên URL và gán vào biến $category
        return view('admin.categories.edit', compact('category'));
    }

    // --- SỬA CHỨC NĂNG UPDATE ---
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update($request->only('name', 'description')); // Chỉ cập nhật các trường cho phép
        
        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật thành công!');
    }

    // --- SỬA CHỨC NĂNG DESTROY ---
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục!');
    }
}