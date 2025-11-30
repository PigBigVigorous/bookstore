<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - BookStore</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        
        .image-card-container {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 12px; /* Bo tròn góc thẻ */
        padding: 20px;       /* Khoảng cách từ viền vào ảnh */
        height: 450px;       /* Chiều cao cố định cho khung thẻ, giúp giao diện gọn */
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05); /* Đổ bóng nhẹ cho thẻ */
    }

    /* CSS MỚI: Tinh chỉnh ảnh bên trong */
    .product-image-large {
        max-width: 100%;     /* Không tràn chiều ngang */
        max-height: 100%;    /* Không tràn chiều dọc */
        width: auto;         /* Tự động theo tỷ lệ */
        height: auto;        /* Tự động theo tỷ lệ */
        object-fit: contain; /* QUAN TRỌNG: Giúp hiện ĐỦ ảnh, không bị cắt */
        filter: drop-shadow(0 5px 5px rgba(0,0,0,0.2)); /* Đổ bóng cho quyển sách nổi lên */
        border-radius: 4px;
        transition: transform 0.3s ease; /* Hiệu ứng khi di chuột */
    }

    .product-image-large:hover {
        transform: scale(1.05); /* Phóng to nhẹ khi di chuột vào sách */
    }
        .price-tag {
            font-size: 2rem;
            color: #dc3545;
            font-weight: bold;
        }
        .related-img {
            height: 250px;
            object-fit: cover;
        }
        .breadcrumb-item a {
            text-decoration: none;
            color: #6c757d;
        }
        .breadcrumb-item a:hover {
            color: #0d6efd;
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
                                {{ array_sum(array_column((array) session('cart'), 'quantity')) }}
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
                    @if($product->category)
                        <li class="breadcrumb-item">
                            <a href="{{ route('home', ['category' => $product->category_id]) }}">
                                {{ $product->category->name }}
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container my-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-5 mb-4">
                <div class="image-card-container">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" class="product-image-large" alt="{{ $product->name }}">
                    @else
                        <div class="text-secondary d-flex flex-column align-items-center">
                            <i class="bi bi-image fs-1 mb-2"></i>
                            <p>Chưa có ảnh bìa</p>
                        </div>
                    @endif
                </div>
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
                    <h5 class="fw-bold border-bottom pb-2">Mô tả sách</h5>
                    <div class="text-secondary" style="white-space: pre-line;">
                        {{ $product->description ?? 'Đang cập nhật mô tả...' }}
                    </div>
                </div>

                @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Số lượng:</label>
                            <div class="input-group" style="width: 140px;">
                                <button class="btn btn-outline-secondary" type="button" id="btn-minus"><i class="bi bi-dash"></i></button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control text-center">
                                <button class="btn btn-outline-secondary" type="button" id="btn-plus"><i class="bi bi-plus"></i></button>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-outline-primary btn-lg px-4 require-login">
                                <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ
                            </button>

                            <button type="submit" name="buy_now" value="true" class="btn btn-danger btn-lg px-5 require-login">
                                <i class="bi bi-lightning-charge-fill me-2"></i> Mua ngay
                            </button>
                        </div>
                    </form>
                @else
                    <button class="btn btn-secondary btn-lg disabled">Tạm hết hàng</button>
                @endif

                <div class="mt-4 pt-3 border-top">
                    <span class="fw-bold me-2">Chia sẻ:</span>
                    <a href="#" class="btn btn-sm btn-primary"><i class="bi bi-facebook"></i> Facebook</a>
                    <a href="#" class="btn btn-sm btn-info text-white"><i class="bi bi-twitter"></i> Twitter</a>
                </div>
            </div>
        </div>
        
        <div class="mt-5">
            <h3 class="fw-bold mb-4 border-start border-4 border-primary ps-3">Sản phẩm liên quan</h3>
            <div class="row">
                @if(isset($relatedProducts) && $relatedProducts->count() > 0)
                    @foreach($relatedProducts as $related)
                        <div class="col-md-3 col-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <a href="{{ route('product.show', $related->id) }}">
                                    @if($related->image)
                                        <img src="{{ Storage::url($related->image) }}" class="card-img-top related-img" alt="{{ $related->name }}">
                                    @else
                                        <div class="bg-secondary text-white d-flex justify-content-center align-items-center related-img">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </a>
                                <div class="card-body">
                                    <h6 class="card-title text-truncate">
                                        <a href="{{ route('product.show', $related->id) }}" class="text-decoration-none text-dark">{{ $related->name }}</a>
                                    </h6>
                                    <p class="card-text text-danger fw-bold">{{ number_format($related->price) }} đ</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Chưa có sản phẩm liên quan nào.</p>
                @endif
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
          <a href="{{ route('login') }}" class="btn btn-primary px-4">Đăng nhập</a>  
          <button type="button" class="btn btn-secondary px-4 me-2" data-bs-dismiss="modal">Để sau</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Logic Auth Modal cũ
            var isLoggedIn = "{{ Auth::check() ? 'true' : 'false' }}" === "true";
            if (typeof bootstrap !== 'undefined') {
                var modalEl = document.getElementById('authRequestModal');
                if (modalEl) {
                    var authModal = new bootstrap.Modal(modalEl);
                    var buttons = document.querySelectorAll('.require-login');
                    buttons.forEach(function(btn) {
                        btn.addEventListener('click', function(e) {
                            if (!isLoggedIn) {
                                e.preventDefault(); 
                                authModal.show();
                            }
                        });
                    });
                }
            }

            // [MỚI] Logic tăng giảm số lượng
            const btnMinus = document.getElementById('btn-minus');
            const btnPlus = document.getElementById('btn-plus');
            const qtyInput = document.getElementById('quantity');

            if(btnMinus && btnPlus && qtyInput) {
                btnMinus.addEventListener('click', function() {
                    let val = parseInt(qtyInput.value);
                    if(val > 1) qtyInput.value = val - 1;
                });

                btnPlus.addEventListener('click', function() {
                    let val = parseInt(qtyInput.value);
                    let max = parseInt(qtyInput.getAttribute('max'));
                    if(val < max) qtyInput.value = val + 1;
                });
            }
        });
    </script>
</body>
</html>