@extends('layouts.app')
@section('title', 'Xác nhận mật khẩu')
@section('content')
    <div class="container mt-5 bg-white p-4 shadow border rounded w-50 mx-auto">
        <div class="mb-3 text-center text-dark fs-3">Xác nhận mật khẩu</div>
        <div class="content w-75 mx-auto">
            <div class="login-form">
                <div class="mb-4 text-sm text-gray-600">
                    {{ __('Đây là khu vực bảo mật của ứng dụng. Vui lòng xác nhận mật khẩu trước khi tiếp tục.') }}
                </div>
                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf
                        <label for="" >Mật khẩu</label>
                        <input class="form-control mb-3 w-100 mx-auto" id="password" type="password" name="password" required>
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
                    <div class="text-end"><button type="submit" class="btn btn-primary" >Xác nhận</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection