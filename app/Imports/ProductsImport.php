<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * Hàm này sẽ chạy cho từng dòng trong file Excel
    */
    public function model(array $row)
    {
        // Kiểm tra dữ liệu bắt buộc (Tên, Tác giả, Giá)
        if(!isset($row['name']) || !isset($row['price'])) {
            return null; // Bỏ qua dòng lỗi
        }

        // Lấy danh mục đầu tiên trong DB để gán mặc định
        // Nếu DB chưa có danh mục nào thì tạo tạm 1 cái
        $category = Category::first();
        if (!$category) {
            $category = Category::create([
                'name' => 'Danh mục chung',
                'description' => 'Tự động tạo khi import'
            ]);
        }

        return new Product([
            'name'        => $row['name'],
            'author'      => $row['author'] ?? 'Unknown', // Mặc định nếu thiếu
            'description' => $row['description'] ?? null,
            'price'       => $row['price'],
            'stock'       => $row['stock'] ?? 10, // Mặc định tồn kho 10
            'category_id' => $category->id, // Gán vào danh mục hợp lệ
            'image'       => null, 
        ]);
    }
}