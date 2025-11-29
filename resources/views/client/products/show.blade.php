<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - BookStore</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        .product-image-large {
            width: 100%;
            height: 500px;
            object-fit: cover; /* Hoặc contain nếu muốn hiển thị full ảnh không bị cắt */
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .price-tag {
            font-size: 2rem;
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">
                <i class="bi bi-book-half"></i> BOOKSTORE
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <form class="d-flex mx-auto my-2 my-lg-0" action="{{ route('home') }}" method="GET">
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
        </div>
    </nav>

    <div class="bg-light py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-5 mb-4">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" class="product-image-large" alt="{{ $product->name }}">
                @else
                    <div class="bg-secondary text-white d-flex justify-content-center align-items-center product-image-large">
                        <i class="bi bi-image fs-1"></i>
                    </div>
                @endif
            </div>

            <div class="col-md-7">
                <h1 class="fw-bold mb-2">{{ $product->name }}</h1>
                <p class="text-muted fs-5 mb-3">
                    Tác giả: <span class="fw-semibold text-dark">{{ $product->author }}</span>
                </p>
                
                <div class="d-flex align-items-center mb-4">
                    <span class="price-tag me-3">{{ number_format($product->price) }} đ</span>
                    @if($product->stock > 0)
                        <span class="badge bg-success fs-6">Còn hàng ({{ $product->stock }})</span>
                    @else
                        <span class="badge bg-danger fs-6">Hết hàng</span>
                    @endif
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold border-bottom pb-2">Mô tả sản phẩm</h5>
                    <div class="text-secondary" style="white-space: pre-line;">
                        {{ $product->description ?? 'Đang cập nhật mô tả...' }}
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    @if($product->stock > 0)
                        <a href="{{ route('cart.add', $product->id) }}" class="btn btn-outline-primary btn-lg px-4 require-login">
                            <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ
                        </a>

                        <a href="{{ route('cart.add', $product->id) }}?buy_now=true" class="btn btn-danger btn-lg px-5 require-login">
                            <i class="bi bi-lightning-charge-fill me-2"></i> Mua ngay
                        </a>
                    @else
                        <button class="btn btn-secondary btn-lg disabled">Tạm hết hàng</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light text-center text-lg-start mt-5 border-top py-4">
        <div class="container text-center text-muted">
            © {{ date('Y') }} Bookstore Project. All rights reserved.
        </div>
    </footer>

    <div class="modal fade" id="authRequestModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header border-bottom-0">
            <h5 class="modal-title fw-bold text-danger">
                <i class="bi bi-exclamation-circle-fill"></i> Yêu cầu đăng nhập
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body py-4">
            <p class="mb-0 fs-5 text-center">Bạn cần đăng nhập để thực hiện mua sắm.</p>
          </div>
          <div class="modal-footer border-top-0 justify-content-center pb-4">
            <button type="button" class="btn btn-secondary px-4 me-2" data-bs-dismiss="modal">Để sau</button>
            <a href="{{ route('login') }}" class="btn btn-primary px-4">Đăng nhập</a>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Kiểm tra trạng thái đăng nhập (đã sửa lỗi cú pháp)
            var isLoggedIn = "{{ Auth::check() ? 'true' : 'false' }}" === "true";
            
            // Khởi tạo Modal
            if (typeof bootstrap !== 'undefined') {
                var modalEl = document.getElementById('authRequestModal');
                if (modalEl) {
                    var authModal = new bootstrap.Modal(modalEl);

                    // Gán sự kiện click cho các nút có class 'require-login'
                    var buttons = document.querySelectorAll('.require-login');
                    buttons.forEach(function(btn) {
                        btn.addEventListener('click', function(e) {
                            if (!isLoggedIn) {
                                e.preventDefault(); // Chặn chuyển trang
                                authModal.show();   // Hiện modal thông báo
                            }
                        });
                    });
                }
            } else {
                console.error('Bootstrap JS chưa được load.');
            }
        });
    </script>
</body>
</html>