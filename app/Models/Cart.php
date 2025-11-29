<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    // Khai báo các cột được phép gán giá trị hàng loạt
    protected $fillable = [
        'user_id', 
        'product_id', 
        'quantity'
    ];

    // Thiết lập mối quan hệ: Một mục giỏ hàng thuộc về một sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    // Thiết lập mối quan hệ: Một mục giỏ hàng thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}