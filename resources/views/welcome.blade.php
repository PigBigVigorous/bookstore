<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>BookStore Online</title>
    <!-- SỬA LINK CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">
                <i class="bi bi-book-half"></i> BOOKSTORE
            </a>
            
            <form class="d-flex mx-auto" action="{{ route('home') }}" method="GET">
                <div class="input-group" style="width: 400px;">
                    <input class="form-control" type="search" name="keyword" placeholder="Tìm sách, tác giả..." value="{{ request('keyword') }}">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('cart.index') }}" class="btn btn-outline-success position-relative me-2">
                    <i class="bi bi-cart3"></i>
                    @if(session('cart'))
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ count((array) session('cart')) }}
                        </span>
                    @endif
                </a>

                @auth
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle border" type="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(Auth::user()->role === 'admin')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Trang Quản Trị</a></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Tài khoản của tôi</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Đăng ký</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Banner -->
    <div class="bg-dark text-white text-center py-5 mb-5" style="background: linear-gradient(45deg, #1a1a1a, #4a4a4a);">
        <div class="container">
            <h1 class="display-4 fw-bold">Thế Giới Sách</h1>
            <p class="lead mb-4">Khám phá tri thức, mở rộng tầm nhìn</p>
        </div>
    </div>

    <!-- Product List -->
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold border-start border-4 border-primary ps-3">Sách Mới Nhất</h3>
        </div>
        
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @forelse($products as $product)
            <div class="col">
                <div class="card h-100 shadow-sm border-0 hover-shadow transition-all">
                    <div class="position-relative overflow-hidden" style="height: 280px;">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" class="card-img-top h-100 w-100 object-fit-cover" alt="{{ $product->name }}">
                        @else
                            <div class="bg-secondary text-white d-flex justify-content-center align-items-center h-100">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif
                        @if($product->stock == 0)
                            <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded small">Hết hàng</div>
                        @endif
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-truncate mb-1" title="{{ $product->name }}">{{ $product->name }}</h5>
                        <small class="text-muted mb-2">{{ $product->author }}</small>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-danger fw-bold fs-5">{{ number_format($product->price) }} đ</span>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline-primary">Xem chi tiết</a>
                                @if($product->stock > 0)
                                    <a href="{{ route('cart.add', $product->id) }}" class="btn btn-primary">
                                        <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="text-muted fs-4">Chưa có sách nào được cập nhật.</div>
            </div>
            @endforelse
        </div>
        
        <div class="mt-5 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start mt-5 border-top">
        <div class="text-center p-3 text-muted">
            © {{ date('Y') }} Bookstore Project. All rights reserved.
        </div>
    </footer>

    <!-- SỬA LINK JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>