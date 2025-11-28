@extends('layouts.admin')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Kho Sách</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Thêm Sách</a>
    </div>
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Tên sách</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Tồn kho</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td style="width: 100px;">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" width="80" alt="img">
                    @else
                        <span>No Image</span>
                    @endif
                </td>
                <td>
                    <strong>{{ $product->name }}</strong><br>
                    <small>{{ $product->author }}</small>
                </td>
                <td>{{ $product->category->name ?? 'N/A' }}</td>
                <td>{{ number_format($product->price) }} đ</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa sách này?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Phân trang -->
    {{ $products->links() }}
@endsection
