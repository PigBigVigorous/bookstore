<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng của bạn</title>
    <link href="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css](https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css)" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Giỏ hàng của bạn</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('cart'))
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0 @endphp
                    @foreach(session('cart') as $id => $details)
                        @php $total += $details['price'] * $details['quantity'] @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($details['image'])
                                        <img src="{{ Storage::url($details['image']) }}" width="50" class="me-3">
                                    @endif
                                    {{ $details['name'] }}
                                </div>
                            </td>
                            <td>{{ number_format($details['price']) }} đ</td>
                            <td style="width: 150px;">
                                <form action="{{ route('cart.update') }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <input type="number" name="quantity" value="{{ $details['quantity'] }}" class="form-control mb-1 text-center" min="1" onchange="this.form.submit()">
                                </form>
                            </td>
                            <td>{{ number_format($details['price'] * $details['quantity']) }} đ</td>
                            <td>
                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <button class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end">
                            <h4>Tổng cộng: <span class="text-danger">{{ number_format($total) }} đ</span></h4>
                            <a href="/" class="btn btn-secondary">Tiếp tục mua</a>
                            <a href="{{ route('checkout.index') }}" class="btn btn-success">Thanh toán</a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="text-center">
                <p class="fs-4">Giỏ hàng trống!</p>
                <a href="/" class="btn btn-primary">Mua ngay</a>
            </div>
        @endif
    </div>
</body>
</html>
