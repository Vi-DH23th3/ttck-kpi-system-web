@extends('layouts.app')
@section('title', 'Bảng điều khiển cá nhân')
@section('content')
<div class="container mt-4">
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
                            {{ Auth::user()->chuc_vu == 'GD' ? 'Giám đốc' : (Auth::user()->chuc_vu == 'TP' ? 'Trưởng phòng' : 'Nhân viên') }}
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

    <div class="row g-4">
        <div class="col-lg-4 col-md-5">
            <div class="sticky-top" style="top: 85px; z-index: 10;">
                <div class="card border-0 shadow-sm mb-4 rounded-3">
                    <div class="card-body p-4">
                        <div class="profile-info">
                            <h6 class="fw-bold text-uppercase small text-primary mb-3">Thông tin chi tiết</h6>
                            <div class="mb-3 d-flex justify-content-between">
                                <span class="text-muted small"><i class="bi bi-building me-2"></i>Phòng ban:</span>
                                <span class="fw-semibold small">{{ Auth::user()->donVi->ten_don_vi }}</span>
                            </div>
                            <div class="mb-3 d-flex justify-content-between">
                                <span class="text-muted small"><i class="bi bi-calendar-check me-2"></i>Ngày tham gia:</span>
                                <span class="fw-semibold small">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small"><i class="bi bi-info-circle me-2"></i>Trạng thái:</span>
                                <span class="badge {{ Auth::user()->trang_thai == 1 ? 'bg-success' : 'bg-danger' }} rounded-pill" style="font-size: 10px;">
                                    {{ Auth::user()->trang_thai == 1 ? 'Hoạt động' : 'Khóa' }}
                                </span>
                            </div>
                        </div>

                        <div class="edit-profile d-none mt-3">
                            <h6 class="fw-bold text-uppercase small text-primary mb-3">Cập nhật thông tin</h6>
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="mb-2">
                                    <label class="small fw-bold">Thay ảnh đại diện</label>
                                    <input type="file" class="form-control form-control-sm" name="avatar">
                                </div>
                                <div class="mb-2">
                                    <input type="text" class="form-control form-control-sm" value="{{ Auth::user()->name }}" name="name" placeholder="Họ tên">
                                </div>
                                <div class="mb-3">
                                    <input type="email" class="form-control form-control-sm" value="{{ Auth::user()->email }}" name="email" placeholder="Email">
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary w-100">Lưu thay đổi</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3 bg-primary text-white">
                    <div class="card-body p-4 text-center">
                        <h6 class="text-uppercase small opacity-75 mb-3">Hiệu suất tháng này</h6>
                        <div class="display-5 fw-bold mb-1">{{ $tongdatduoc / $tongchitieu * 100 }}%</div>
                        <p class="small mb-3">Đã hoàn thành {{ $tongdatduoc }}/{{ $tongchitieu }} chỉ tiêu</p>
                        <div class="progress bg-white bg-opacity-25" style="height: 6px;">
                            <div class="progress-bar bg-white" style="width: {{ $tongdatduoc / $tongchitieu * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <ul class="nav nav-tabs nav-tabs-bordered" id="profileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold py-3" data-bs-toggle="tab" data-bs-target="#tasks">
                                <i class="bi bi-list-task me-1"></i> Việc cần làm
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold py-3" data-bs-toggle="tab" data-bs-target="#history">
                                <i class="bi bi-clock-history me-1"></i> Lịch sử báo cáo
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content pt-2">
                        <div class="tab-pane fade show active" id="tasks">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="small text-muted table-light">
                                        <tr>
                                            <th>Nội dung KPI</th>
                                            <th>Tiến độ</th>
                                            <th class="text-center">Mức độ ưu tiên</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dscongviec as $cv)
                                        <tr>
                                            <td style="max-width: 250px;">
                                                <div class="fw-bold mb-1 text-truncate">{{ $cv->thuVienKPI->ten_kpi }}</div>
                                                <small class="text-muted">Chu kỳ: {{ $cv->thuVienKPI->chu_ky }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress w-100 me-2" style="height: 6px;">
                                                        <div class="progress-bar bg-success" style="width: {{ ($cv->thuc_te_dat_duoc / $cv->thuVienKPI->chi_tieu) * 100 }}%"></div>
                                                    </div>
                                                    <small class="fw-bold">{{ $cv->thuc_te_dat_duoc }}/{{ $cv->thuVienKPI->chi_tieu }}</small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($cv->muc_do_uu_tien == '1')
                                                    <span class="badge bg-danger">Cao</span>
                                                @elseif($cv->muc_do_uu_tien == '2')
                                                    <span class="badge bg-warning">Trung bình</span>
                                                @else
                                                    <span class="badge bg-success">Thấp</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($cv->baoCaoCongViec->where('trangthai_duyet', 'tra_lai')->count() > 0)
                                                <button class="btn btn-sm btn-light border btn-nop-bao-cao" 
                                                        data-id="{{ $cv->id }}" 
                                                        data-ten="{{ $cv->thuVienKPI->ten_kpi }}">
                                                    Báo cáo lại
                                                </button>
                                                @elseif($cv->thuc_te_dat_duoc == $cv->thuVienKPI->chi_tieu)
                                                    <span class="text-success small">Đã hoàn thành</span>
                                                @else
                                                <button class="btn btn-sm btn-light border btn-nop-bao-cao" 
                                                        data-id="{{ $cv->id }}" 
                                                        data-ten="{{ $cv->thuVienKPI->ten_kpi }}">
                                                    Nộp báo cáo
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="history">
                            @if($baoCao)
                                <table class="table table-hover align-middle">
                                    <thead class="small text-muted table-light">
                                        <tr>
                                            <th>Nội dung KPI</th>
                                            <th>Ngày thực hiện</th>
                                            <th>Người duyệt</th>
                                            <th>Ghi chú</th>
                                            <th>Trạng thái duyệt</th>
                                            <!-- <th class="text-center">Thao tác</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($baoCao as $bc)
                                        <tr>
                                            <td style="max-width: 250px;">
                                                <div class="fw-bold mb-1 text-truncate">{{ $bc->phanCong->thuVienKPI->ten_kpi }}</div>
                                                <small class="text-muted">Chu kỳ: {{ $bc->phanCong->thuVienKPI->chu_ky }}</small>
                                            </td>
                                            <td>
                                                {{ $bc->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="text-center">
                                                {{ $bc->userDuyet->name ?? 'Chưa có người duyệt'}}
                                            </td>
                                            <td>
                                                {{ $bc->ghi_chu }}
                                            </td>
                                            <td>
                                                @if($bc->trangthai_duyet == 'chua_duyet')
                                                    <span class="text-danger">Chưa được duyệt</span>
                                                @elseif($bc->trangthai_duyet == 'da_duyet')
                                                    <span class="text-success">Đã được duyệt</span>
                                                @else
                                                    <span class="text-warning">Đã bị trả lại</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                            <div class="text-center py-5">
                                <i class="bi bi-folder2-open display-4 text-muted"></i>
                                <p class="mt-2 text-muted">Danh sách các báo cáo đã nộp sẽ hiện ở đây.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalNopBaoCao" tabindex="-1" aria-labelledby="modalNopBaoCaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalNopBaoCaoLabel"><i class="bi bi-file-earmark-arrow-up me-2"></i>Nộp Báo Cáo Tiến Độ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formNopBaoCao" action="{{ route('profile.storebaocao') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-secondary py-2">
                        <small class="d-block">KPI: <strong id="display_ten_kpi">...</strong></small>
                        <small>Chỉ tiêu: <strong id="display_chi_tieu">...</strong></small>
                    </div>
                    <input type="hidden" name="phan_cong_cong_viec_id" id="input_phan_cong_cong_viec_id">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tiến độ thực hiện thực tế</label>
                            <input type="number" name="tien_do" class="form-control" required placeholder="Nhập con số kết quả...">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ngày báo cáo</label>
                            <input type="date" name="ngay_bao_cao" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="trang_thai_bao_cao" class="form-label fw-bold">Trạng thái</label>
                            <!-- đang làm, đã nộp, đã chỉnh sửa -->
                            <select name="trang_thai_bao_cao" id="trang_thai_bao_cao" class="form-select">
                                <option value="">Chọn trạng thái</option>
                                <option value="dang_lam">Đang thực hiện</option>
                                <option value="da_nop">Đã nộp</option>
                                <option value="da_chinh_sua">Đã chỉnh sửa</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Nội dung giải trình / Minh chứng</label>
                            <textarea name="ghi_chu" class="form-control" rows="3" placeholder="Mô tả chi tiết công việc đã làm..."></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Tài liệu đính kèm (nếu có)</label>
                            <input type="file" name="file_minh_chung" class="form-control">
                            <div class="form-text">Chấp nhận file PDF, Word, Excel, Hình ảnh (Max 5MB)</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitBaoCao">Gửi báo cáo</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
    let profile =document.querySelector('.profile-info');
    let edit_profile = document.querySelector('.edit-profile');
    let btnEdit = document.querySelector('.btn-edit');

    btnEdit.addEventListener('click', function() {
        // const isEditing = !edit_profile.classList.contains('d-none');
        if (edit_profile.classList.contains('d-none')) {
            profile.classList.add('d-none');
            edit_profile.classList.remove('d-none');
            this.innerHTML = '<i class="bi bi-x-circle me-1"></i> Hủy sửa';
            this.classList.replace('btn-outline-primary', 'btn-outline-danger');
        } else {
            profile.classList.remove('d-none');
            edit_profile.classList.add('d-none');
        this.innerHTML = '<i class="bi bi-pencil-square me-1"></i> Chỉnh sửa hồ sơ';
        this.classList.replace('btn-outline-danger', 'btn-outline-primary');
    }
});
</script>

<script src="{{ asset('js/congvieccanhan.js') }}"></script>
@endpush

@endsection