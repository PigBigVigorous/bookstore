@extends('layouts.admin')
@section('content')
    <h2>Thêm Danh mục</h2>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Tên danh mục</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
    </form>
@endsection
