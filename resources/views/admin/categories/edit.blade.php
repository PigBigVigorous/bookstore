@extends('layouts.admin')
@section('content')
    <h2>Sửa Danh mục</h2>
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Tên danh mục</label>
            <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-warning">Cập nhật</button>
    </form>
@endsection
