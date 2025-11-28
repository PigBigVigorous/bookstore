<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Find category by name or create a new one
        $category = Category::where('name', $row['category'])->first();
        
        if (!$category) {
            $category = Category::create([
                'name' => $row['category'],
                'slug' => Str::slug($row['category'])
            ]);
        }

        return new Product([
            'name' => $row['name'],
            'description' => $row['description'] ?? '',
            'price' => $row['price'],
            'stock' => $row['stock'],
            'category_id' => $category->id,
            'image' => $row['image'] ?? null,
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}