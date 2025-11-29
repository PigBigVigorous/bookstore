<x-guest-layout>
    <div class="text-center mb-4">
        <h4 class="fw-bold">Đăng nhập</h4>
        <p class="text-muted small">Chào mừng bạn quay trở lại!</p>
    </div>

    <x-auth-session-status class="mb-3 text-success" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">{{ __('Email') }}</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">{{ __('Mật khẩu') }}</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                   name="password" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label small text-muted">{{ __('Ghi nhớ tôi') }}</label>
            </div>
            
            @if (Route::has('password.request'))
                <a class="text-decoration-none small text-primary" href="{{ route('password.request') }}">
                    {{ __('Quên mật khẩu?') }}
                </a>
            @endif
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm">
                {{ __('Đăng nhập') }}
            </button>
        </div>

        <hr class="my-4">

        <div class="text-center">
            <span class="text-muted small">Chưa có tài khoản?</span>
            <a href="{{ route('register') }}" class="text-decoration-none fw-bold text-primary ms-1">Đăng ký ngay</a>
        </div>
    </form>
</x-guest-layout>