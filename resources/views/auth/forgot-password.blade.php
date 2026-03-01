@extends('layouts.app')
@section('title', 'Quên mật khẩu')
@section('content')
    <div class="container mt-5 bg-white p-4 shadow border rounded w-50 mx-auto">
        <div class="mb-3 text-center text-dark fs-3">Quên mật khẩu</div>
        <div class="content w-75 mx-auto">
            <div class="login-form">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                        <label for="" >Email</label>    
                        <input class="form-control mb-3 w-100 mx-auto" id="email" type="email" name="email" value="{{ old('email') }}" required>
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
                    <div class="text-end"><button type="submit" class="btn btn-primary" >Gửi liên kết đặt lại mật khẩu</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection