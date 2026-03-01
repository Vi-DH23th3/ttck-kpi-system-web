@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
    <div class="main-content p-4 vh-100">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded">
                <div class="">
                    <button class="btn btn-outline-secondary m-3 border-0" onclick="toggleSidebar()">☰</button>
                    <form class="form-control d-inline-flex w-auto" method="GET" action="">
                                 
                        <input type="text" class="border-0" placeholder="Search">  
                        <button type="submit" class="btn btn-outline-secondary border-0"><i class="bi bi-search"></i></button> 
                    </form>           
                </div>
                <div class="">
                    <span class="text-muted me-3"><a href="{{ route('profile.edit') }}" class="text-decoration-none text-muted">Xin chào, {{ Auth::user()->name }}<i class="bi bi-people"></i></a></span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm border-0"><i class="bi bi-box-arrow-right"></i> Đăng xuất</button>
                    </form> 
                </div>
        </div>
        <h2 class="text-dark">Chào mừng bạn đến với hệ thống KPI</h2>
    </div>
@endsection