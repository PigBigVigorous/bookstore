@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4">Chỉnh Sửa Sách</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Danh sách</a></li>
        <li class="breadcrumb-item active">{{ $product->name }}</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Cập nhật thông tin
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf 
                @method('PUT') <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên sách <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                               class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tác giả <span class="text-danger">*</span></label>
                        <input type="text" name="author" value="{{ old('author', $product->author) }}" 
                               class="form-control @error('author') is-invalid @enderror" required>
                        @error('author')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giá tiền (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" 
                               class="form-control @error('price') is-invalid @enderror" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số lượng tồn</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" 
                               class="form-control @error('stock') is-invalid @enderror">
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" 
                                    {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Thay đổi ảnh bìa (Để trống nếu giữ nguyên)</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        @if($product->image)
                            <div class="mt-2">
                                <label>Ảnh hiện tại:</label><br>
                                <img src="{{ Storage::url($product->image) }}" width="120" class="img-thumbnail rounded" alt="Current Image">
                            </div>
                        @endif
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-pencil-square"></i> Cập nhật Sách
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection