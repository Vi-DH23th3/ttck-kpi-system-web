@extends('layouts.app')
@section('title', 'Đăng ký')
@section('content')
    <div class="container mt-5 bg-white p-4 shadow border rounded w-50 mx-auto">
        <div class="mb-3 text-center text-dark fs-3">Đăng ký</div>
        <div class="content w-75 mx-auto">
            <div class="login-form">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                        <label for="" >Tên người dùng</label>    
                        <input class="form-control mb-3 w-100 mx-auto" id="name" type="text" name="name" value="{{ old('name') }}" required>
                        <label for="" >Email</label>    
                        <input class="form-control mb-3 w-100 mx-auto" id="email" type="email" name="email" value="{{ old('email') }}" required>
                        <label for="" >Mật khẩu</label>
                        <input class="form-control mb-3 w-100 mx-auto" id="password" type="password" name="password" required>
                        <label for="" >Xác nhận mật khẩu</label>
                        <input class="form-control mb-3 w-100 mx-auto" id="password_confirmation" type="password" name="password_confirmation" required>
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
                    <div class="text-end">
                        <a href="{{route('login')}}" class="btn btn-link text-muted">Đã có tài khoản? Đăng nhập</a>
                        <button type="submit" class="btn btn-primary">Đăng ký</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection