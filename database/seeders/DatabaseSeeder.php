<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Chỉ giữ lại tài khoản Admin để quản trị
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        // Đã xóa (comment) phần tạo User thường, Danh mục, Sản phẩm và Đơn hàng mẫu
        /*
        $user = User::create([
            'name' => 'Khách Hàng A',
            'email' => 'khach@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'user',
        ]);

        // ... (Các phần tạo Category, Product, Order cũ đã được loại bỏ)
        */
    }
}