<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} - BookStore</title>
    <!-- SỬA LINK CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">BOOKSTORE</a>
            <div class="d-flex gap-2">
                <a href="{{ route('cart.index') }}" class="btn btn-outline-success">
                    <i class="bi bi-cart"></i> Giỏ hàng 
                    <span class="badge bg-success">{{ count((array) session('cart')) }}</span>
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
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif
        
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row">
                    <!-- Cột Trái: Ảnh -->
                    <div class="col-md-5 text-center">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" class="img-fluid rounded shadow-sm" alt="{{ $product->name }}" style="max-height: 500px;">
                        @else
                            <div class="bg-secondary text-white d-flex justify-content-center align-items-center rounded" style="height: 400px;">
                                No Image
                            </div>
                        @endif
                    </div>

                    <!-- Cột Phải: Thông tin -->
                    <div class="col-md-7">
                        <h1 class="fw-bold mb-2">{{ $product->name }}</h1>
                        <p class="text-muted fs-5 mb-4">Tác giả: <span class="text-dark">{{ $product->author }}</span></p>
                        
                        <h2 class="text-danger fw-bold mb-4">{{ number_format($product->price) }} đ</h2>
                        
                        <div class="mb-4">
                            <h5 class="fw-bold">Mô tả sản phẩm:</h5>
                            <p class="text-secondary">{{ $product->description ?? 'Chưa có mô tả cho cuốn sách này.' }}</p>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <span class="me-3">Tình trạng:</span> 
                            @if($product->stock > 0)
                                <span class="badge bg-success fs-6">Còn hàng ({{ $product->stock }})</span>
                            @else
                                <span class="badge bg-danger fs-6">Hết hàng</span>
                            @endif
                        </div>

                        <div class="d-flex gap-3">
                            <!-- Nút Thêm vào giỏ -->
                            @if($product->stock > 0)
                                <a href="{{ route('cart.add', $product->id) }}" class="btn btn-primary btn-lg px-5">
                                    <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                </a>
                            @else
                                <button class="btn btn-secondary btn-lg px-5" disabled>Tạm hết hàng</button>
                            @endif
                            
                            <a href="/" class="btn btn-outline-secondary btn-lg">Quay lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>