<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bookstore</title>
    <!-- Đã sửa link CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark p-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">BOOKSTORE ADMIN</a>
            <div class="d-flex">
                <span class="text-white me-3">Hi, {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light btn-sm" type="submit">Đăng xuất</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 bg-light min-vh-100 p-3">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="{{ route('admin.categories.index') }}">Quản lý Danh mục</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="{{ route('admin.products.index') }}">Quản lý Sách</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="{{ route('admin.orders.index') }}">Quản lý Đơn hàng</a>
                    </li>
                </ul>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 p-4">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Đã sửa link JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>