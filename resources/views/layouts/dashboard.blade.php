@extends('layouts.admin')

@section('content')
    <h2>Tổng quan</h2>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Sản phẩm</h5>
                    <p class="card-text fs-4">Quản lý kho sách</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Đơn hàng</h5>
                    <p class="card-text fs-4">Đơn hàng mới</p>
                </div>
            </div>
        </div>
    </div>
@endsection
