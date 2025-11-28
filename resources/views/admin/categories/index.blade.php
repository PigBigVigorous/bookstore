@extends('layouts.admin')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Danh mục sách</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Thêm mới</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr><th>ID</th><th>Tên</th><th>Hành động</th></tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
            <tr>
                <td>{{ $cat->id }}</td>
                <td>{{ $cat->name }}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Chắc chắn xóa?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
