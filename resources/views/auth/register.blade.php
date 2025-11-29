<x-guest-layout>
    <div class="text-center mb-4">
        <h4 class="fw-bold">Đăng ký tài khoản</h4>
        <p class="text-muted small">Tạo tài khoản để mua sách dễ dàng hơn</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">{{ __('Họ và Tên') }}</label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                   name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">{{ __('Email') }}</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">{{ __('Mật khẩu') }}</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                   name="password" required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label fw-semibold">{{ __('Xác nhận mật khẩu') }}</label>
            <input id="password_confirmation" type="password" class="form-control" 
                   name="password_confirmation" required autocomplete="new-password">
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm">
                {{ __('Đăng ký') }}
            </button>
        </div>

        <hr class="my-4">

        <div class="text-center">
            <span class="text-muted small">Đã có tài khoản?</span>
            <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-primary ms-1">Đăng nhập</a>
        </div>
    </form>
</x-guest-layout>