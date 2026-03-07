@extends('layouts.app')
@section('title', 'Trang cá nhân')
@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white shadow-sm p-4 rounded-2 border gap-2">
            
            <div class="d-flex align-items-center">
                <div class="position-relative">
                    <img src="{{asset('storage/' . Auth::user()->avatar)}}" class="rounded-circle me-3" alt="Avatar" style="width: 80px; height: 80px; object-fit: cover;">
                </div>
                <div>
                    <h5 class="fw-bold mb-0">{{Auth::user()->name}}</h5>
                    <p class="text-muted mb-0">{{Auth::user()->email}}</p>
                </div>
            </div>
            <button class="btn btn-primary px-4 btn-edit" style="border-radius: 10px;" type="button">Edit</button>
        </div>

        <div class="row g-4">
            <div class="col-md-4 bg">
                <div class="p-3 border bg-light shadow-sm p-4 rounded-2 sticky-top" style="top:70px; z-index: 1000;">
                        <div class="profile">
                            <label class="form-label fw-semibold">Thông tin cá nhân</label>
                            <p class="text-muted" ><i class="bi bi-person-fill"></i> Họ tên: <strong>{{Auth::user()->name}}</strong></p>
                            <p class="text-muted" ><i class="bi bi-briefcase-fill"></i> Chức vụ: <strong>{{Auth::user()->chuc_vu == 'GD' ? 'Giám đốc' : 
                                    (Auth::user()->chuc_vu == 'TP' ? 'Trưởng phòng' : 
                                    (Auth::user()->chuc_vu == 'PTP' ? 'Phó trưởng phòng' : 'Nhân viên'))}}</strong></p>
                            <p class="text-muted" ><i class="bi bi-building-gear"></i> Phòng ban: <strong>{{Auth::user()->donVi->ten_don_vi}}</strong></p>
                            <p class="text-muted" ><i class="bi bi-envelope-at-fill"></i> Email: <strong>{{Auth::user()->email}}</strong></p>
                            <p class="text-muted" ><i class="bi bi-check-circle-fill"></i> Trạng thái: <strong>{{Auth::user()->trang_thai == '1' ? 'Hoạt động' : 'Bị khóa'}}</strong></p>
                        </div> 
                        <!-- Form sửa thông tin cá nhân -->
                        <div class="edit-profile d-none">
                            <label class="form-label fw-semibold">Sửa thông tin cá nhân</label>
                            <form action="{{ route('profile.update') }}" method="POST" class="form-control w-100"  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <label for="">Avatar</label>
                                <input type="file" class="form-control mb-3" name="avatar">
                                <input class="text-muted form-control mb-3" type="text" value="{{Auth::user()->name}}" name="name">
                                <input class="text-muted form-control mb-3" type="text" value="{{Auth::user()->email}}" name="email">
                                <button tyle="submit" class="btn btn-primary">Cập nhật</button>
                            </form> 
                        </div> 
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                </div>         
            </div>
            <div class="col-md-8">
                <!-- <a class="form-label fw-semibold" href="">Danh sách công việc</a> -->
                <div class="p-3 border bg-light shadow-sm p-4 rounded-2">
                <h5 class="card-header fw-semibold">Danh sách công việc</h5>
                <div class="table-responsive mb-3 fw-semibold">
                    <table class="table datatable-project">
                    <thead class="table-light">
                        <tr>
                        <th></th>
                        <th></th>
                        <th class="fw-semibold">Project</th>
                        <th class="text-nowrap fw-semibold">Total Task</th>
                        <th class="fw-semibold">Progress</th>
                        <th class="fw-semibold">Hours</th>
                        </tr>
                    </thead>
                    </table>
                </div>
                </div>
                
                <!--/ Projects table -->
            </div>
            </div>
        </div>                                 
</div>
<script>
    let profile =document.querySelector('.profile');
    let edit_profile = document.querySelector('.edit-profile');
    let btnEdit = document.querySelector('.btn-edit');

    btnEdit.addEventListener('click', function() {
        if (edit_profile.classList.contains('d-none')) {
            profile.classList.add('d-none');
            edit_profile.classList.remove('d-none');
        }else{
            profile.classList.remove('d-none');
            edit_profile.classList.add('d-none');
        }
    });
</script>
@endsection