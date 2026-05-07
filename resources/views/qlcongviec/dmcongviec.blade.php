@extends('layouts.admin')
@section('title', 'Danh mục công việc')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y mt-3">
    
    <div class="d-flex justify-content-between align-items-center mb-4 px-2">
        <h4 class="fw-bold text-dark mb-0">
            <i class="bi bi-book-half me-2 text-primary"></i>Danh sách công việc
        </h4>
        <button class="btn btn-primary shadow-sm btn-add-DMCV px-4 fw-bold" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddDMCV" aria-controls="offcanvasAddDMCV">
            <i class="bi bi-plus-lg me-1"></i>Thêm công việc
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{route('system.dmcongviec.index')}}" class="btn {{ !request('id') ? 'btn-primary' : 'btn-outline-secondary border-0' }} rounded-pill px-4 d-flex align-items-center shadow-sm">
                    <span class="fw-bold me-2">Tất cả</span>
                    <span class="badge {{ !request('id') ? 'bg-white text-primary' : 'bg-secondary' }} rounded-pill">{{ $tongviec }}</span>
                </a>
                
                @foreach($dsdv as $dv) 
                    <form action="" method="GET" class="m-0">
                        <input type="hidden" value="{{$dv->id}}" name="id">
                        <button type="submit" class="btn {{ request('id') == $dv->id ? 'btn-primary' : 'btn-outline-secondary border-0' }} rounded-pill px-3 d-flex align-items-center shadow-sm">
                            <span class="fw-bold me-2">{{$dv->ten_don_vi}}</span>
                            <span class="badge {{ request('id') == $dv->id ? 'bg-white text-primary' : 'bg-secondary' }} rounded-pill">
                                {{ $dv->danh_muc_count ?? 0 }}
                            </span>
                        </button>
                    </form>
                @endforeach 
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr >
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-white bg-primary" style="width: 50%">Tên công việc</th>
                        <th class="text-center text-uppercase small fw-bold text-white bg-primary">Thư viện KPI</th>
                        <th class="pe-4 text-center text-uppercase small fw-bold text-white bg-primary">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dmcv as $cv)
                        <tr class="cv-row-{{ $cv->id }}">
                            <td class="ps-4">
                                <span class="fw-semibold text-dark col-name">{{$cv->ten_cong_viec}}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('system.qlcongviec.thuvienkpi', ['dm_id' => $cv->id]) }}" class="btn btn-sm btn-label-info rounded-pill px-3 fw-bold border-0" style="background-color: #e1f5fe; color: #0288d1;">
                                    <i class="bi bi-collection me-1"></i> 
                                    {{ $cv->thu_vien_k_p_i_count ?? 0 }} KPI mẫu
                                </a>
                            </td>
                            <td class="text-lg-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                <button class="btn btn-sm btn-outline-primary btn_edit_cv" data-cv-id="{{ $cv->id }}" type="button" title='Sửa'><i class="bi bi-pencil"></i></button>
                                <form class="border-0" action="{{route('system.dmcongviec.destroy',$cv->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn_delete_cv" onclick="xacNhan(this)" data-message="Xác nhận xóa danh mục công việc này này?"><i class="bi bi-trash"></i></button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-end">
                {{-- {{ $dmcv->links() }} --}}
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="offcanvasAddDMCV">
        <div class="offcanvas-header bg-primary text-white py-4">
            <h5 class="offcanvas-title fw-bold text-white"><i class="bi bi-plus-circle me-2"></i>Thêm công việc mới</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form id="addDMCV-listListForm" action="" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">Tên công việc</label>
                    <input type="text" class="form-control border-2 add-name" placeholder="Ví dụ: Giảng dạy chuyên môn..." name="name_DMCV" value="{{ old('name_DMCV') }}">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">Đơn vị quản lý</label>
                    <select name="don_vi_id" class="form-select border-2 add-donvi">
                        <option value="0">--- Chọn đơn vị ---</option>
                        @foreach($dsdv as $dv)
                            <option value="{{$dv->id}}">{{$dv->ten_don_vi}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-grid gap-2 mt-5">
                    <button type="button" class="btn btn-primary btn-lg fw-bold add-submit shadow-sm" onclick="xacNhan(this)" data-message="Xác nhận thêm danh mục công việc này?">Lưu công việc</button>
                    <button type="reset" class="btn btn-light btn-lg text-muted" data-bs-dismiss="offcanvas">Hủy bỏ</button>
                </div>
            </form>
        </div>
    </div>

    <div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="offcanvasEditDMCV">
        <div class="offcanvas-header bg-info text-white py-4">
            <h5 class="offcanvas-title fw-bold text-white"><i class="bi bi-pencil-square me-2"></i>Chỉnh sửa công việc</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form id="formUpdateDMCV-listListForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">Tên công việc hiện tại</label>
                    <input type="text" class="form-control border-2 edit-name" name="name_DMCV" value="{{ old('name_DMCV') }}">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">Đơn vị quản lý</label>
                    <select name="don_vi_id" class="form-select border-2 add-donvi" id="dmEditSelect">
                        <option value="0">--- Chọn đơn vị ---</option>
                        @foreach($dsdv as $dv)
                            <option value="{{$dv->id}}">{{$dv->ten_don_vi}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-grid gap-2 mt-5">
                    <button type="button" class="btn btn-info text-white btn-lg fw-bold edit-submit shadow-sm" onclick="xacNhan(this)" data-message="Xác nhận sửa danh mục công việc này?">Cập nhật thay đổi</button>
                    <button type="reset" class="btn btn-light btn-lg text-muted" data-bs-dismiss="offcanvas">Quay lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script src="{{ asset('js/dmcongviec.js') }}"></script>
    <script src="{{ asset('js/thongbaoxacnhan.js') }}"></script>
@endpush
@endsection