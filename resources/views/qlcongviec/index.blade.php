@extends('layout.admin')
@section('title', 'Quản lý công việc')

@section('content')
 <div class="container-xxl flex-grow-1 container-p-y">
    <h3 class="text-muted">Tống quan</h3>
    <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-2">
        <div class="bg-info rounded-2 w-25">
            <p>Tổng việc</p>
            <p>0</p>
        </div>
        <div class="bg-secondary rounded-2 w-25">
            <p>Đang thực hiện</p>
            <p>0</p>
        </div>
        <div class="bg-warning rounded-2 w-25">
            <p>Chờ phê duyệt</p>
            <p>0</p>
        </div>
        <div class="bg-danger rounded-2 w-25">
            <p>Quá hạn</p>
            <p>0</p>
        </div>
    </div>
 </div>
@endsection