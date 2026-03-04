@extends('layouts.app')
@section('title', 'Đổi mật khẩu')
@section('content')
    <div class="container mt-5 bg-white p-4 shadow border rounded w-50 mx-auto">
        <div class="mb-3 text-center text-dark fs-3">Đổi mật khẩu</div>
        <div class="content w-75 mx-auto">
            <div class="login-form">
                <form method="POST" action="{{ route('password.change.update') }}">
                    @csrf
                        <label for="" >Mật khẩu</label>
                        <input class="form-control mb-3 w-100 mx-auto" id="password" type="password" name="password" required>
                    <div class="error">
                        @if ($errors->any())
                            <div class="alert-error">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-danger">{{ $error }}</li>
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

@if (session('info'))
    @push('script')
        <script>
        
            Swal.fire({
                icon: "info",      
                title: "Thông báo",
                text: "{{ session('info') }}", 
                showConfirmButton: false,      
                timer: 3000,                  
                timerProgressBar: true         
            });
        </script>
    @endpush
@endif
@endsection