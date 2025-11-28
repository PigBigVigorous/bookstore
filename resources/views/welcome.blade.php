<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>BookStore Online</title>
    <link href="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css](https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css)" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">BOOKSTORE</a>
            
            <form class="d-flex mx-auto" action="{{ route('home') }}" method="GET">
                <input class="form-control me-2" type="search" name="keyword" placeholder="Tìm sách..." style="width: 300px;">
                <button class="btn btn-outline-success" type="submit">Tìm</button>
            </form>

            <div class="d-flex gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">Tài khoản</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Đăng ký</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Banner -->
    <div class="bg-dark text-white text-center py-5">
        <h1>Chào mừng đến với thế giới sách</h1>
        <p>Tìm kiếm những cuốn sách hay nhất tại đây</p>
    </div>

    <!-- Product List -->
    <div class="container my-5">
        <h3 class="mb-4 text-center">Sách Mới Nhất</h3>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach($products as $product)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" class="card-img-top" alt="..." style="height: 250px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex justify-content-center align-items-center" style="height: 250px;">No Image</div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title text-truncate">{{ $product->name }}</h5>
                        <p class="card-text text-danger fw-bold">{{ number_format($product->price) }} đ</p>
                        <a href="{{ route('product.show', $product->id) }}" class="btn btn-primary w-100">Xem chi tiết</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</body>
</html>

