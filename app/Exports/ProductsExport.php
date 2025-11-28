<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::with('category')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tên Sách',
            'Tác Giả',
            'Giá',
            'Tồn Kho',
            'Danh Mục',
            'Mô Tả',
            'Ngày Tạo'
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->author,
            $product->price,
            $product->stock,
            $product->category ? $product->category->name : 'Không có',
            $product->description,
            $product->created_at->format('d/m/Y'),
        ];
    }
}