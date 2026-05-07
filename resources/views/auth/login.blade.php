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
                    <div class="remember-forgot text-end mb-3">
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                            Quên mật khẩu?
                        </a>
                    </div>

                    <div class="modal fade" id="forgotPasswordModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Quên mật khẩu?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <p>Vui lòng liên hệ trực tiếp với <strong>Phòng Quản trị Hệ thống</strong> hoặc <strong>Admin</strong> để được cấp lại mật khẩu mới.</p>
                                    <p class="text-muted small">Hotline: 0123.456.789</p>
                                </div>
                            </div>
                        </div>
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