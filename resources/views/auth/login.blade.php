@extends('layouts.app')
@section('title', 'Đăng nhập')

@section('content')
    <div class="container mt-5 bg-white p-4 shadow border rounded w-50 mx-auto">
        <div class="mb-3 text-center text-primary fs-3">Đăng nhập</div>
        <div class="content w-75 mx-auto">
            <div class="login-form">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                        <label for="" >Email</label>    
                        <input class="form-control mb-3 w-100 mx-auto" id="email" type="email" name="email" value="{{ old('email') }}" required>
                        <label for="" >Mật khẩu</label>
                        <input class="form-control mb-3 w-100 mx-auto" id="password" type="password" name="password" required>
                    <div class="remember-forgot d-flex justify-content-between mb-3">
                        <label><input type="checkbox" name="remember" > Ghi nhớ đăng nhập</label>                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                        @endif
                    </div>
                    <div class="error">
                        @if ($errors->any())
                            <div class="alert-error">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="text-end"><button type="submit" class="btn btn-primary w-100" >Đăng nhập</button></div>
                </form>
                <hr>
                <div class="mt-4">
                    <a href="{{ route('login.google') }}" class="btn btn-outline-dark w-100" style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google Logo" width="20">
                        Đăng nhập với Google
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection