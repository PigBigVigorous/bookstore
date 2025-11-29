<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>BookStore Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        /* Container giữ hình ảnh - thiết lập khung cố định */
        .product-img-container {
            position: relative;
            height: 280px;
            overflow: hidden;
            background: #f8f9fa;
            cursor: pointer;
        }
        
        /* Ảnh mặc định (Thumb) - cắt ảnh cho vừa khung */
        .product-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.3s;
        }

        /* Lớp phủ chứa ảnh Full (ẩn mặc định) */
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

        /* Ảnh full bên trong lớp phủ - hiển thị trọn vẹn (contain) */
        .product-full-img-overlay img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain; 
        }

        /* Hiệu ứng: Khi di chuột vào container thì hiện lớp phủ */
        .product-img-container:hover .product-full-img-overlay {
            opacity: 1;
            visibility: visible;
        }

        /* Hiệu ứng đổ bóng nhẹ khi hover vào Card sản phẩm */
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
        .transition-all {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
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

    <div class="bg-dark text-white text-center py-5 mb-5" style="background: linear-gradient(45deg, #1a1a1a, #4a4a4a);">
        <div class="container">
            <h1 class="display-4 fw-bold">Thế Giới Sách</h1>
            <p class="lead mb-4">Khám phá tri thức, mở rộng tầm nhìn</p>
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
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline-primary">
                                            Xem chi tiết
                                        </a>

                                        @if($product->stock > 0)
                                            <a href="{{ route('cart.add', $product->id) }}" class="btn btn-primary">
                                                <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                            </a>

                                            <a href="{{ route('cart.add', $product->id) }}?buy_now=true" class="btn btn-danger">
                                                <i class="bi bi-lightning-charge-fill"></i> Mua ngay
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-warning d-inline-block px-5">
                            <i class="bi bi-exclamation-circle me-2"></i> Không tìm thấy sách nào phù hợp với bộ lọc.
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>