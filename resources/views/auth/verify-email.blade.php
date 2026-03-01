@extends('layouts.app')
@section('title', 'Xác nhận email')
@section('content')
    <div class="container mt-5 bg-white p-4 shadow border rounded w-50 mx-auto">
        <div class="mb-3 text-center text-dark fs-3">Xác nhận email</div>
        <div class="content w-75 mx-auto">
            <div class="login-form">
                <div class="mb-4 text-sm text-gray-600">
                    {{ __('Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, bạn có thể xác minh địa chỉ email của mình bằng cách nhấp vào liên kết chúng tôi vừa gửi cho bạn qua email không? Nếu bạn không nhận được email, chúng tôi sẽ vui lòng gửi lại cho bạn một cái khác.') }}
                </div>
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ __('Một liên kết xác minh mới đã được gửi đến địa chỉ email mà bạn đã cung cấp trong quá trình đăng ký.') }}
                    </div>
                @endif
                <div class="mt-4 flex items-center justify-between">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <div>
                            <button type="submit" class="btn btn-primary">
                                {{ __('Gửi lại email xác minh') }}
                            </button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Đăng xuất') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>      
@endsection