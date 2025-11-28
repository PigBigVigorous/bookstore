<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng của bạn</title>
    <!-- SỬA LINK CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navbar đơn giản -->
    <nav class="navbar navbar-light bg-white border-bottom mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">BOOKSTORE</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4">Giỏ hàng của bạn</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('cart'))
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Sản phẩm</th>
                                <th>Giá</th>
                                <th class="text-center">Số lượng</th>
                                <th>Thành tiền</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0 @endphp
                            @foreach(session('cart') as $id => $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if(isset($details['image']))
                                                <img src="{{ Storage::url($details['image']) }}" width="60" class="me-3 rounded border">
                                            @else
                                                <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 80px; color: white;">Img</div>
                                            @endif
                                            <span class="fw-bold">{{ $details['name'] }}</span>
                                        </div>
                                    </td>
                                    <td>{{ number_format($details['price']) }} đ</td>
                                    <td style="width: 120px;">
                                        <form action="{{ route('cart.update') }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <input type="number" name="quantity" value="{{ $details['quantity'] }}" class="form-control text-center" min="1" onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="fw-bold text-primary">{{ number_format($details['price'] * $details['quantity']) }} đ</td>
                                    <td>
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button class="btn btn-outline-danger btn-sm border-0"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="d-flex justify-content-end align-items-center mt-4 p-3 bg-light rounded">
                <h4 class="me-4 mb-0">Tổng cộng: <span class="text-danger fw-bold">{{ number_format($total) }} đ</span></h4>
                <div>
                    <a href="/" class="btn btn-outline-secondary me-2">Tiếp tục mua</a>
                    <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg">Thanh toán ngay</a>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-cart-x display-1 text-muted"></i>
                </div>
                <p class="fs-4 text-muted">Giỏ hàng của bạn đang trống!</p>
                <a href="/" class="btn btn-primary mt-3">Mua sắm ngay</a>
            </div>
        @endif
    </div>
</body>
</html>