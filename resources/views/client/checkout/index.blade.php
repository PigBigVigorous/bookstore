<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh Toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Xác nhận đơn hàng</h2>
        <div class="row">
            <div class="col-md-7">
                <div class="card p-4">
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Họ tên người nhận</label>
                            <input type="text" name="receiver_name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="receiver_phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ giao hàng</label>
                            <textarea name="receiver_address" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="note" class="form-control" rows="2"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2">Xác nhận đặt hàng</button>
                    </form>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card bg-light p-4">
                    <h4>Đơn hàng của bạn</h4>
                    <hr>
                    @php $total = 0; @endphp
                    @foreach($cart as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                            <span class="fw-bold">{{ number_format($item['price'] * $item['quantity']) }} đ</span>
                        </div>
                        @php $total += $item['price'] * $item['quantity']; @endphp
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between fs-5 text-danger fw-bold">
                        <span>Tổng cộng:</span>
                        <span>{{ number_format($total) }} đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>