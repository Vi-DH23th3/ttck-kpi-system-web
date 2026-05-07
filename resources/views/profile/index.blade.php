@extends('layouts.admin')
@section('title', 'Bảng điều khiển cá nhân')
@section('content')
<div class="container-fluid mt-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                             class="rounded-circle border border-3 border-primary p-1" 
                             alt="Avatar" style="width: 90px; height: 90px; object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success border border-white p-2">
                            <span class="visually-hidden">Online</span>
                        </span>
                    </div>
                    <div class="ms-4">
                        <h4 class="fw-bold mb-1 text-dark">{{ Auth::user()->name }}</h4> 
                        <p class="text-muted mb-0"><i class="bi bi-envelope me-1"></i> {{ Auth::user()->email }}</p>
                        <span class="badge bg-soft-primary text-primary mt-2">
                            {{ Auth::user()->ChucVu->ten_chuc_vu }}
                        </span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-edit" type="button">
                        <i class="bi bi-pencil-square me-1"></i> Chỉnh sửa hồ sơ
                    </button>
                    <form action="{{route('password.change')}}" method="GET">
                        @csrf
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-shield-lock me-1"></i> Đổi mật khẩu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::user()->role == 'staff')
    <div class="col-lg-12 col-md-12">
        <ul class="nav nav-tabs nav-tabs-bordered sticky-top" id="profileTab" role="tablist" style="top: 70px; z-index: 1000;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold py-3" data-bs-toggle="tab" data-bs-target="#profile">
                    <i class="bi bi-list-task me-1"></i> Thông tin cá nhân
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-3" data-bs-toggle="tab" data-bs-target="#project">
                    <i class="bi bi-clock-history me-1"></i> Danh sách công việc
                </button>
            </li>
        </ul>   
    </div>
    <div class="tab-content" id="profileTabContent">
        
        @include('profile.nhanvien.profile')
        @include('profile.nhanvien.dscongviec')
    </div> 
    @endif
    @if(Auth::user()->role == 'manager')
    <div class="row">
        <div class="col-md-4">
            @include('profile.quanly.profile')
        </div>
        <div class="col-md-8">
            @include('profile.quanly.dscongviec')
        </div>
    </div>
    @endif
    @if(Auth::user()->role == 'admin')
    <div class="row">
        <div class="col-md-4">
            @include('profile.admin.thongtincanhan')
        </div>
        <div class="col-md-8">
            @include('profile.admin.tongquan')
        </div>
    </div>
       
    @endif
     
    
</div>
@include('profile.formnopbaocao')

@push('script')
<script src="{{ asset('js/congvieccanhan.js') }}"></script>
<script src="{{ asset('js/thongbaoxacnhan.js') }}"></script>
@endpush

@endsection
