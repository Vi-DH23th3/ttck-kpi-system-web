@extends('layouts.admin')
@section('title', 'Người dùng')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    
    <div class="d-flex justify-content-between align-items-center mb-4 px-2 mt-3">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-white shadow-sm btn-add-user rounded-3" style="background: white;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddFilter">
                <i class="bi bi-filter text-primary fs-5"></i>
            </button>
            <h4 class="fw-bold text-dark mb-0">
                <i class="bi bi-people me-2 text-primary"></i>Danh sách người dùng
            </h4>
        </div>
        
        <div class="d-flex gap-2">
            @can('admin')
            <button class="btn btn-primary shadow-sm btn-add-user px-4 fw-bold" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser">
                <i class="bi bi-plus-lg me-1"></i>Thêm người dùng
            </button>
            @endcan
        </div>
    </div>
@can('admin')
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body py-2">
                    <form class="d-flex align-items-center gap-2" method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data">
                        @csrf
                        <small class="text-muted fw-bold text-nowrap">NHẬP TỪ EXCEL:</small>
                        <input type="file" name="import_file" id="import_file" class="form-control form-control-sm border-2 rounded-3" accept=".xlsx, .xls">
                        <button type="submit" class="btn btn-success btn-sm px-3 fw-bold btn-import text-nowrap">Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endcan
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div id="user-data-container" class="table-user">
                @include('users.table')
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start border-0 shadow" tabindex="-1" id="offcanvasAddFilter">
        <div class="offcanvas-header bg-light py-4">
            <h5 class="offcanvas-title fw-bold"><i class="bi bi-funnel me-2"></i>Bộ lọc tìm kiếm</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4">
            <p class="text-muted small mb-4">Hệ thống sẽ tự động tải lại sau khi bạn chọn điều kiện lọc.</p>
            
            <div class="mb-3">
                <label class="form-label fw-bold small">Đơn vị / Phòng ban</label>
                <form method="GET" action="">
                    <select name="filter_don_vi" class="form-select border-2" onchange="this.form.submit()">
                        <option value="">-- Tất cả đơn vị --</option>
                        @foreach($dsdonvi as $dsdv)
                            <option value="{{$dsdv->id}}" {{ request('filter_don_vi') == $dsdv->id ? 'selected' : '' }}>{{$dsdv->ten_don_vi}}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small">Chức vụ</label>
                <form method="GET" action="">
                    <select name="filter_chucvu" class="form-select border-2" onchange="this.form.submit()">
                        <option value="">-- Tất cả chức vụ --</option>
                        @foreach($dschucvu as $cv)
                            <option value="{{$cv->id}}" {{ request('filter_chucvu') == $cv->id ? 'selected' : '' }}>{{$cv->ten_chuc_vu}}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small">Trạng thái</label>
                <form method="GET" action="">
                    <select name="filter_trang_thai" class="form-select border-2" onchange="this.form.submit()">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="1" {{ request('filter_trang_thai') == '1' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ request('filter_trang_thai') == '0' ? 'selected' : '' }}>Bị khóa</option>
                    </select>
                </form>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small">Quyền hạn</label>
                <form method="GET" action="">
                    <select name="filter_role" class="form-select border-2" onchange="this.form.submit()">
                        <option value="">-- Tất cả quyền --</option>
                        <option value="admin" {{ request('filter_role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ request('filter_role') == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="staff" {{ request('filter_role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </form>
            </div>

            <div class="d-grid mt-5">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Xóa tất cả bộ lọc</a>
            </div>
        </div>
    </div>

    @include('users.formthem')
    @include('users.formsua')
</div>
@push('script')
  <script src="{{ asset('js/user.js') }}"></script>
  <script src="{{ asset('js/thongbaoxacnhan.js') }}"></script>
@endpush
@endsection