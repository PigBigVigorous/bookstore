<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tạo Users
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        $user = User::create([
            'name' => 'Khách Hàng A',
            'email' => 'khach@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'user',
        ]);

        // 2. Tạo Categories
        $cat1 = Category::create(['name' => 'Văn Học', 'description' => 'Các tác phẩm văn học kinh điển và hiện đại']);
        $cat2 = Category::create(['name' => 'Kinh Tế', 'description' => 'Sách về kinh doanh, đầu tư và tài chính']);
        $cat3 = Category::create(['name' => 'Thiếu Nhi', 'description' => 'Sách truyện dành cho lứa tuổi thiếu nhi']);

        // 3. Tạo Products
        // Sách Văn Học
        $p1 = Product::create([
            'name' => 'Nhà Giả Kim',
            'author' => 'Paulo Coelho',
            'description' => 'Tiểu thuyết về hành trình theo đuổi ước mơ.',
            'price' => 79000,
            'stock' => 50,
            'category_id' => $cat1->id,
            'image' => null,
        ]);

        $p2 = Product::create([
            'name' => 'Tắt Đèn',
            'author' => 'Ngô Tất Tố',
            'description' => 'Tác phẩm văn học hiện thực phê phán.',
            'price' => 45000,
            'stock' => 100,
            'category_id' => $cat1->id,
            'image' => null,
        ]);

        // Sách Kinh Tế
        $p3 = Product::create([
            'name' => 'Cha Giàu Cha Nghèo',
            'author' => 'Robert Kiyosaki',
            'description' => 'Bài học về tư duy tài chính.',
            'price' => 110000,
            'stock' => 30,
            'category_id' => $cat2->id,
            'image' => null,
        ]);

        // Sách Thiếu Nhi
        $p4 = Product::create([
            'name' => 'Dế Mèn Phiêu Lưu Ký',
            'author' => 'Tô Hoài',
            'description' => 'Câu chuyện phiêu lưu của chú dế mèn.',
            'price' => 35000,
            'stock' => 200,
            'category_id' => $cat3->id,
            'image' => null,
        ]);

        // 4. Tạo Order mẫu cho Khách hàng
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 155000, // (79000 * 1) + (35000 * 2) + ship... ví dụ
            'status' => 'pending',
            'payment_method' => 'cod',
        ]);

        // 5. Tạo Order Details
        OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $p1->id, // Mua 1 cuốn Nhà Giả Kim
            'quantity' => 1,
            'price' => 79000,
        ]);

        OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $p4->id, // Mua 2 cuốn Dế Mèn
            'quantity' => 2,
            'price' => 35000,
        ]);
    }
}