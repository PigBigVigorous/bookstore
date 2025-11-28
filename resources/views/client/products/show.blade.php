<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} - BookStore</title>
    <link href="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css](https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css)" rel="stylesheet">
</head>
<body>
    <!-- Navbar (Giống trang chủ) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">BOOKSTORE</a>
            <div class="d-flex gap-2">
                <a href="{{ route('cart.index') }}" class="btn btn-outline-success">
                    Giỏ hàng <span class="badge bg-success">{{ count((array) session('cart')) }}</span>
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">Tài khoản</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">Đăng nhập</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Product Detail -->
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <div class="row">
            <!-- Cột Trái: Ảnh -->
            <div class="col-md-5">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" class="img-fluid rounded shadow" alt="{{ $product->name }}">
                @else
                    <div class="bg-secondary text-white d-flex justify-content-center align-items-center rounded" style="height: 400px;">
                        No Image
                    </div>
                @endif
            </div>

            <!-- Cột Phải: Thông tin -->
            <div class="col-md-7">
                <h1 class="fw-bold">{{ $product->name }}</h1>
                <p class="text-muted fs-5">Tác giả: {{ $product->author }}</p>
                <h3 class="text-danger fw-bold my-3">{{ number_format($product->price) }} đ</h3>
                
                <p class="mt-4"><strong>Mô tả:</strong></p>
                <p>{{ $product->description ?? 'Đang cập nhật...' }}</p>

                <div class="mt-5">
                    <p>Tình trạng: 
                        @if($product->stock > 0)
                            <span class="text-success fw-bold">Còn hàng ({{ $product->stock }})</span>
                        @else
                            <span class="text-danger fw-bold">Hết hàng</span>
                        @endif
                    </p>

                    <!-- Nút Thêm vào giỏ -->
                    @if($product->stock > 0)
                        <a href="{{ route('cart.add', $product->id) }}" class="btn btn-primary btn-lg px-5">
                            Thêm vào giỏ hàng
                        </a>
                    @else
                        <button class="btn btn-secondary btn-lg px-5" disabled>Hết hàng</button>
                    @endif
                    
                    <a href="/" class="btn btn-outline-secondary btn-lg ms-2">Quay lại</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
