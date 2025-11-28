<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::with('category')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Price',
            'Stock',
            'Category',
            'Image',
            'Created At',
            'Updated At'
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->description,
            $product->price,
            $product->stock,
            $product->category->name ?? 'No Category',
            $product->image,
            $product->created_at,
            $product->updated_at
        ];
    }
}