@extends('layouts.admin')
@section('content')
    <h2>Sửa Sách: {{ $product->name }}</h2>
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Tên sách</label>
                <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Tác giả</label>
                <input type="text" name="author" class="form-control" value="{{ $product->author }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Giá tiền</label>
                <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Số lượng tồn</label>
                <input type="number" name="stock" class="form-control" value="{{ $product->stock }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>Danh mục</label>
                <select name="category_id" class="form-control">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Ảnh bìa</label>
                <input type="file" name="image" class="form-control">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" width="100" class="mt-2" alt="Current Image">
                @endif
            </div>
            <div class="col-12 mb-3">
                <label>Mô tả</label>
                <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-warning">Cập nhật Sách</button>
    </form>
@endsection
