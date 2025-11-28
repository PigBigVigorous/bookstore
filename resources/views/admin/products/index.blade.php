@extends('layouts.admin')
@section('content')
    <div class="mb-4">
        <h2>Quản lý Kho Sách</h2>
    </div>

    <!-- Toolbar: Tìm kiếm -->
    <div class="row mb-3 align-items-center">
        <div class="col-md-4">
            <!-- Form Tìm kiếm -->
            <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Tìm tên sách hoặc tác giả..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Tìm</button>
            </form>
        </div>
        
        <div class="col-md-8 text-end">
            <!-- Nút chức năng -->
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Thêm Sách
            </a>
        </div>
    </div>

    <!-- Bảng danh sách -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sách</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td style="width: 80px;">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="img-thumbnail" width="60" alt="img">
                            @else
                                <span class="badge bg-secondary">No Image</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong><br>
                            <small class="text-muted">{{ $product->author }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $product->category->name ?? 'N/A' }}</span>
                        </td>
                        <td class="fw-bold text-danger">{{ number_format($product->price) }} đ</td>
                        <td>
                            @if($product->stock > 0)
                                <span class="text-success">{{ $product->stock }}</span>
                            @else
                                <span class="badge bg-danger">Hết hàng</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa sách này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Không tìm thấy sách nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Phân trang -->
    <div class="mt-3">
        {{ $products->withQueryString()->links() }}
    </div>
@endsection