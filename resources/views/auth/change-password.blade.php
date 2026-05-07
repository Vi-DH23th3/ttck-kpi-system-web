@extends('layouts.app')
@section('title', 'Đổi mật khẩu')
@section('content')
    <div class="container mt-5 bg-white p-4 shadow border rounded w-50 mx-auto">
        <div class="mb-3 text-center text-primary fw-bold fs-3">
            <i class="bi bi-shield-lock"></i> Đổi mật khẩu
        </div>
        <div class="content w-75 mx-auto">
            <div class="login-form">
                <form method="POST" action="{{ route('password.change.update') }}">
                    @csrf
                    <label>Mật khẩu hiện tại</label>
                    <input class="form-control mb-3" id="old_password" type="password" name="old_password" required value="{{old('old_password')}}">

                    <label>Nhập mật khẩu mới</label>
                    <input class="form-control mb-3" id="new_password" type="password" name="new_password" required value="{{old('new_password')}}">

                    <label>Xác nhận mật khẩu mới</label>
                    <input class="form-control mb-3" id="new_password_confirmation" type="password" name="new_password_confirmation" required value="{{old('new_password_confirmation')}}">
                    <div class="error">

                    </div>
                    <div class="text-end"><button type="button" class="btn btn-primary" onclick="xacNhan(this)" data-message="Xác nhận đổi mật khẩu?">Xác nhận</button></div>
                </form>
            </div>
        </div>
    </div>   
@push('script')
   <script src="{{ asset('js/thongbaoxacnhan.js') }}"></script>
@endpush
@endsection
