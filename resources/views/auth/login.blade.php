@extends('layouts.app')
@section('title', 'Đăng nhập')

@section('content')
    <div class="container mt-5 bg-white p-4 shadow border rounded w-50 mx-auto">
        <div class="mb-3 text-center text-dark fs-3">Đăng nhập</div>
        <div class="content w-75 mx-auto">
            <div class="login-form">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                        <label for="" >Email</label>    
                        <input class="form-control mb-3 w-100 mx-auto" id="email" type="email" name="email" value="{{ old('email') }}" required>
                        <label for="" >Mật khẩu</label>
                        <input class="form-control mb-3 w-100 mx-auto" id="password" type="password" name="password" required>
                    <div class="remember-forgot d-flex justify-content-between mb-3">
                        <label><input type="checkbox" name="remember" > Ghi nhớ đăng nhập</label>
                        <a href="{{route('register')}}" class="text-muted">Chưa có tài khoản? Đăng ký</a>
                        @if (Route::has('password.request'))
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
                    <div class="text-end"><button type="submit" class="btn btn-primary" >Đăng nhập</button></div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection