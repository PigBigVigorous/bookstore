<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    // Tạo tài khoản Admin
        User::factory()->create([
        'name' => 'Administrator',
        'email' => 'admin@gmail.com',
        'password' => bcrypt('123456'), // Mật khẩu 123456
        'role' => 'admin',
    ]);

    // Tạo tài khoản Khách
        User::factory()->create([
        'name' => 'Khách Hàng A',
        'email' => 'khach@gmail.com',
        'password' => bcrypt('123456'),
        'role' => 'user',
    ]);
    }
}
