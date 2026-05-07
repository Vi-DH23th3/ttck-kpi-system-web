@extends('layouts.admin')
@section('title', 'Chức vụ')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="d-flex justify-content-between align-items-center mb-4 px-2 mt-3">
        <h4 class="fw-bold text-dark mb-0">
            <i class="bi bi-briefcase me-2 text-primary"></i>Danh sách chức vụ
        </h4>
        @can('admin')
        <button class="btn btn-primary shadow-sm btn-add-ChucVu px-4 fw-bold" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddChucVu">
            <i class="bi bi-plus-lg me-1"></i> Thêm chức vụ mới
        </button>
        @endcan
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-muted small fw-bold text-uppercase">
                        <th class="ps-4 py-3" style="width: 80px;">STT</th>
                        <th>Tên chức vụ</th>
                        <th class="pe-4 text-center" style="width: 150px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chucvu as $index => $cv)
                    <tr class="chucvu-row-{{ $cv->id }}">
                        <td class="ps-4 text-muted fw-semibold">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="badge bg-label-primary p-2 me-3 rounded">
                                    <i class="bi bi-person-badge text-primary"></i>
                                </div>
                                <span class="fw-bold text-dark">{{ $cv->ten_chuc_vu }}</span>
                            </div>
                        </td>
                        <td class="pe-4 text-center">
                            @can('admin')
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-outline-primary border-0 bg-light btn_edit_chucvu" 
                                        data-url="{{ route('admin.chucvu.edit', $cv->id) }}"
                                        title="Chỉnh sửa">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                
                                <form action="{{route('admin.chucvu.destroy',$cv->id)}}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger border-0 bg-light" 
                                            onclick="xacNhan(this)" data-message="Xác nhận xóa chức vụ?">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="offcanvasAddChucVu" aria-labelledby="addChucVuLabel">
        <div class="offcanvas-header bg-primary text-white py-4">
            <h5 id="addChucVuLabel" class="offcanvas-title fw-bold text-white"><i class="bi bi-plus-circle me-2"></i>Thêm chức vụ</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form id="addChucVu-listListForm" action="{{route('admin.chucvu.store')}}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">Tên chức vụ</label>
                    <input type="text" class="form-control border-2 add-name" placeholder="Ví dụ: Trưởng phòng, Nhân viên..." name="ten_chuc_vu">
                    <div class="form-text mt-2 small text-muted italic">Lưu ý: Tên chức vụ nên ngắn gọn và rõ ràng.</div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary btn-lg fw-bold add-submit shadow-sm" onclick="xacNhan(this)" data-message="Xác nhận thêm chức vụ?">Lưu thông tin</button>
                    <button type="button" class="btn btn-light btn-lg text-muted" data-bs-dismiss="offcanvas">Hủy</button>
                </div>
            </form>
        </div>
    </div>

    <div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="offcanvasEditChucVu" aria-labelledby="editChucVuLabel">
        <div class="offcanvas-header bg-info text-white py-4">
            <h5 id="editChucVuLabel" class="offcanvas-title fw-bold text-white"><i class="bi bi-pencil-square me-2"></i>Sửa chức vụ</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form id="eCommerceChucVu-listListForm" class="form-edit" method="POST">      
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">Tên chức vụ hiện tại</label>
                    <input type="text" class="form-control border-2 edit-name" id="edit-name" name="ten_chuc_vu">
                </div>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-info text-white btn-lg fw-bold edit-submit shadow-sm" onclick="xacNhan(this)" data-message="Xác nhận cập nhật chức vụ?">Cập nhật thay đổi</button>
                    <button type="button" class="btn btn-light btn-lg" data-bs-dismiss="offcanvas">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script src="{{ asset('js/chucvu.js') }}"></script>
    <script src="{{ asset('js/thongbaoxacnhan.js') }}"></script>
@endpush

@endsection