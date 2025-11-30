<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookStore Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        /* CSS cho Ảnh sản phẩm & Overlay */
        .product-img-container {
            position: relative;
            height: 280px;
            overflow: hidden;
            background: #f8f9fa;
            cursor: pointer;
        }
        .product-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.3s;
        }
        .product-full-img-overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 10;
            border-bottom: 1px solid #eee;
        }
        .product-full-img-overlay img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain; 
        }
        .product-img-container:hover .product-full-img-overlay {
            opacity: 1;
            visibility: visible;
        }
        
        /* Hiệu ứng Hover Card */
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
        .transition-all {
            transition: all 0.3s ease;
        }

        /* Banner */
        .hero-banner {
            background-image: url('{{ asset("images/banner-main.jpg") }}');
            background-size: cover;
            background-position: center;
            height: 450px;
            display: flex;
            align-items: center;
            position: relative;
        }

        /* Ẩn text phân trang mặc định của Bootstrap (Showing results...) */
        nav .d-none.flex-sm-fill.d-sm-flex.align-items-sm-center.justify-content-sm-between > div:first-child {
            display: none;
        }
        nav .d-none.flex-sm-fill.d-sm-flex.align-items-sm-center.justify-content-sm-between {
            justify-content: center !important;
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
                
                <ul class="navbar-nav me-3 mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-grid-fill text-secondary me-1"></i> Danh Mục
                        </a>
                        <ul class="dropdown-menu shadow border-0 mt-2">
                            <li><a class="dropdown-item fw-bold" href="{{ route('home', ['category' => 'all']) }}">Tất cả sách</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @if(isset($categories))
                                @foreach($categories as $cat)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('home', ['category' => $cat->id]) }}">
                                            {{ $cat->name }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </li>
                </ul>

                <form class="d-flex flex-grow-1 mx-lg-4 my-2 my-lg-0" action="{{ route('home') }}" method="GET">
                    <div class="input-group">
                        <input class="form-control border-end-0" type="search" name="keyword" placeholder="Tìm kiếm sách, tác giả..." value="{{ request('keyword') }}">
                        <button class="btn btn-outline-secondary border-start-0 bg-white text-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <a href="{{ route('cart.index') }}" class="btn btn-light position-relative border">
                        <i class="bi bi-cart3 fs-5"></i>
                        @if(session('cart'))
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ array_sum(array_column((array) session('cart'), 'quantity')) }}
                            </span>
                        @endif
                    </a>

                    @auth
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle px-3" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Trang Quản Trị</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear me-2"></i>Tài khoản của tôi</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="d-flex gap-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary fw-bold">Đăng nhập</a>
                            <a href="{{ route('register') }}" class="btn btn-primary fw-bold">Đăng ký</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="hero-banner text-white text-center py-5 mb-5">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0, 0, 0, 0.6);"></div>
        <div class="container position-relative" style="z-index: 2;">
            <h1 class="display-3 fw-bold mb-3">Thế Giới Sách - BookStore</h1>
            <p class="lead mb-4 fs-4 text-light">Khám phá tri thức - Mở rộng tầm nhìn</p>
        </div>
    </div>

    <div class="container my-5">
        <div class="row">
            
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 90px; z-index: 1;">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="bi bi-funnel"></i> Bộ Lọc Tìm Kiếm
                    </div>
                    <div class="card-body">
                        <form action="{{ route('home') }}" method="GET">
                            @if(request('keyword'))
                                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                            @endif

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-uppercase text-muted">Thể Loại</label>
                                <select name="category" class="form-select">
                                    <option value="all">Tất cả thể loại</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-uppercase text-muted">Khoảng Giá (VNĐ)</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-light">Từ</span>
                                    <input type="number" name="price_min" class="form-control" value="{{ request('price_min') }}" min="0" placeholder="0">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Đến</span>
                                    <input type="number" name="price_max" class="form-control" value="{{ request('price_max') }}" min="0" placeholder="Max">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-uppercase text-muted">Sắp xếp</label>
                                <select name="sort" class="form-select">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                                </select>
                            </div>

                            <hr>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> Áp dụng
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-counterclockwise"></i> Xóa bộ lọc
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold border-start border-4 border-primary ps-3 mb-0">Sách Mới Nhất</h3>
                    <span class="text-muted small">Hiển thị {{ $products->count() }} / {{ $products->total() }} kết quả</span>
                </div>
                
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @forelse($products as $product)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 hover-shadow transition-all">
                        
                        <div class="product-img-container">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="product-thumb" alt="{{ $product->name }}">
                                <div class="product-full-img-overlay">
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                </div>
                            @else
                                <div class="bg-secondary text-white d-flex justify-content-center align-items-center h-100 w-100">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            @endif

                            @if($product->stock == 0)
                                <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded small shadow-sm" style="z-index: 20;">Hết hàng</div>
                            @endif
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate mb-1" title="{{ $product->name }}">
                                <a href="{{ route('product.show', $product->id) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                            </h5>
                            <small class="text-muted mb-2"><i class="bi bi-pen"></i> {{ $product->author }}</small>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-danger fw-bold fs-5">{{ number_format($product->price) }} đ</span>
                                </div>

                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        
                                        <div class="input-group mb-3 input-group-sm">
                                            <span class="input-group-text">SL:</span>
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control text-center">
                                        </div>

                                        <div class="d-grid gap-2">
                                            <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline-primary">
                                                Xem chi tiết
                                            </a>

                                            <button type="submit" class="btn btn-primary require-login">
                                                <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                            </button>

                                            <button type="submit" name="buy_now" value="true" class="btn btn-danger require-login">
                                                <i class="bi bi-lightning-charge-fill"></i> Mua ngay
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline-primary">Xem chi tiết</a>
                                        <button class="btn btn-secondary" disabled>Hết hàng</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="alert alert-warning d-inline-block px-5">
                        <i class="bi bi-exclamation-circle me-2"></i> Không tìm thấy sách nào phù hợp.
                    </div>
                </div>
                @endforelse
            </div>
                
                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light text-center text-lg-start mt-5 border-top">
        <div class="text-center p-3 text-muted">
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
            <a href="{{ route('login') }}" class="btn btn-primary px-4 me-2">Đăng nhập</a>
            <button type="button" class="btn btn-secondary px-4 me-2" data-bs-dismiss="modal">Để sau</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Kiểm tra trạng thái đăng nhập
            var isLoggedIn = "{{ Auth::check() ? 'true' : 'false' }}" === "true";
            
            // 2. Kiểm tra xem thư viện Bootstrap đã được load chưa
            if (typeof bootstrap === 'undefined') {
                console.error('Lỗi: Thư viện Bootstrap chưa được load!');
                return;
            }

            // 3. Khởi tạo Modal
            var modalElement = document.getElementById('authRequestModal');
            if (modalElement) {
                var authModal = new bootstrap.Modal(modalElement);

                // 4. Lắng nghe sự kiện click vào nút cần đăng nhập
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
        });
    </script>
</body>
</html>