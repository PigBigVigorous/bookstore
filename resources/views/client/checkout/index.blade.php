<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <link href="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css](https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css)" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Xác nhận đơn hàng</h2>
        <div class="row">
            <div class="col-md-6">
                <h4>Thông tin người nhận</h4>
                <div class="card p-3 mb-3">
                    <p><strong>Họ tên:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p class="text-muted">Ghi chú: Đơn hàng sẽ được giao đến địa chỉ mặc định trong hồ sơ.</p>
                </div>
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success w-100 py-2">XÁC NHẬN ĐẶT HÀNG (COD)</button>
                </form>
            </div>
            <div class="col-md-6">
                <h4>Đơn hàng của bạn</h4>
                <ul class="list-group mb-3">
                    @php $total = 0; @endphp
                    @foreach($cart as $item)
                        @php $total += $item['price'] * $item['quantity']; @endphp
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0">{{ $item['name'] }}</h6>
                                <small class="text-muted">Số lượng: {{ $item['quantity'] }}</small>
                            </div>
                            <span class="text-muted">{{ number_format($item['price'] * $item['quantity']) }} đ</span>
                        </li>
                    @endforeach
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <span class="fw-bold">Tổng tiền (VND)</span>
                        <span class="fw-bold text-danger">{{ number_format($total) }} đ</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
