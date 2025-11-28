@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="mt-4">Sửa Danh mục</h2>
        
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                    @csrf 
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" 
                               class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Thêm phần description nếu database có --}}
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-warning">Cập nhật</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
@endsection