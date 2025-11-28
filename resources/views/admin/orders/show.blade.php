@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chi tiết Đơn hàng #{{ $order->id }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>

    <div class="row">
        <!-- Cột Thông tin đơn hàng -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">Thông tin Khách hàng</div>
                <div class="card-body">
                    <p><strong>Họ tên:</strong> {{ $order->user->name }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                    <p><strong>Phương thức TT:</strong> {{ strtoupper($order->payment_method) }}</p>
                </div>
            </div>

            <!-- Form cập nhật trạng thái -->
            <div class="card">
                <div class="card-header bg-warning text-dark">Cập nhật Trạng thái</div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label">Trạng thái hiện tại:</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Hủy đơn</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cột Chi tiết sản phẩm -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Danh sách sản phẩm</div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->details as $detail)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($detail->product && $detail->product->image)
                                            <img src="{{ Storage::url($detail->product->image) }}" width="50" class="me-2">
                                        @endif
                                        {{ $detail->product->name ?? 'Sản phẩm đã xóa' }}
                                    </div>
                                </td>
                                <td>{{ number_format($detail->price) }} đ</td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="fw-bold">{{ number_format($detail->price * $detail->quantity) }} đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">TỔNG TIỀN:</td>
                                <td class="fw-bold text-danger fs-5">{{ number_format($order->total_price) }} đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="mt-3 text-end">
                 <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này không? Hành động này không thể hoàn tác!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa Đơn Hàng</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection