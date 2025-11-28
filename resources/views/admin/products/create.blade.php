@extends('layouts.admin')
@section('content')
    <h2>Thêm Sách Mới</h2>
    <!-- enctype="multipart/form-data" BẮT BUỘC để upload ảnh -->
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Tên sách</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Tác giả</label>
                <input type="text" name="author" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Giá tiền</label>
                <input type="number" name="price" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Số lượng tồn</label>
                <input type="number" name="stock" class="form-control" value="10">
            </div>
            <div class="col-md-6 mb-3">
                <label>Danh mục</label>
                <select name="category_id" class="form-control">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Ảnh bìa</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="col-12 mb-3">
                <label>Mô tả</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Lưu Sách</button>
    </form>
@endsection
