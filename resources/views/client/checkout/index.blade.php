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
                <div class="card p-4 shadow-sm">
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ tên người nhận</label>
                            <input type="text" name="receiver_name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" name="receiver_phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa chỉ giao hàng</label>
                            <textarea name="receiver_address" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="note" class="form-control" rows="2" placeholder="Ví dụ: Giao giờ hành chính..."></textarea>
                        </div>
                        
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary w-50 py-2">
                                Xác nhận đặt hàng
                            </button>
                            <a href="{{ route('checkout.cancel') }}" class="btn btn-secondary w-50 py-2">
                                Huỷ bỏ
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card bg-light p-4 border-0">
                    <h4 class="mb-3">Đơn hàng của bạn</h4>
                    <hr>
                    @php $total = 0; @endphp
                    
                    @if(count($cart) > 0)
                        @foreach($cart as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <span class="fw-medium">{{ $item['name'] }}</span> <br>
                                    <small class="text-muted">x {{ $item['quantity'] }}</small>
                                </div>
                                <span class="fw-bold">{{ number_format($item['price'] * $item['quantity']) }} đ</span>
                            </div>
                            @php $total += $item['price'] * $item['quantity']; @endphp
                        @endforeach
                    @else
                        <div class="text-center text-muted">Không có sản phẩm nào.</div>
                    @endif
                    
                    <hr>
                    <div class="d-flex justify-content-between fs-5 text-danger fw-bold">
                        <span>Tổng cộng:</span>
                        <span>{{ number_format($total) }} đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>