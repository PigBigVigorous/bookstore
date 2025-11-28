@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Quản lý Đơn hàng</h2>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>
                            <div>{{ $order->user->name }}</div>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        </td>
                        <td class="fw-bold text-danger">{{ number_format($order->total_price) }} đ</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @php
                                $badges = [
                                    'pending' => 'bg-warning text-dark',
                                    'approved' => 'bg-info text-white',
                                    'shipping' => 'bg-primary',
                                    'completed' => 'bg-success',
                                    'canceled' => 'bg-danger'
                                ];
                                $statusLabels = [
                                    'pending' => 'Chờ xử lý',
                                    'approved' => 'Đã duyệt',
                                    'shipping' => 'Đang giao',
                                    'completed' => 'Hoàn thành',
                                    'canceled' => 'Đã hủy'
                                ];
                            @endphp
                            <span class="badge {{ $badges[$order->status] ?? 'bg-secondary' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                Xem chi tiết
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Chưa có đơn hàng nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-3">
        {{ $orders->links() }}
    </div>
</div>
@endsection