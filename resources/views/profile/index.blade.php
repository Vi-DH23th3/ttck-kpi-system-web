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
                            
                            <th>Nội dung công việc</th>
                            <th>Chỉ tiêu</th>
                            <th>Đơn vị</th>
                            <th>Chu kỳ</th>
                            <th>Người giao</th>
                            <th>Trạng thái</th>
                            <th>Nộp báo cáo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dscongviec as $cv)
                        <tr>
                           
                            <td>{{ $cv->thuVienKPI->ten_kpi }}</td>
                            <td>{{ $cv->thuVienKPI->chi_tieu }}</td>
                            <td>{{ $cv->thuVienKPI->don_vi }}</td>
                            <td>{{ $cv->thuVienKPI->chu_ky }}</td>
                            <td>{{ $cv->nguoiGiao->name }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">{{ $cv->trang_thai == 'chua_bat_dau' ? 'Chờ thực hiện' : 
                                    ($cv->trang_thai == 'dang_thuc_hien' ? 'Đang thực hiện' : 
                                    ($cv->trang_thai == 'da_hoan_thanh' ? 'Đã hoàn thành' : 'Đã hủy')) }}</span>
                            </td>
                            <td><button type="button" class="btn btn-sm btn-primary btn-nop-bao-cao" 
                                    data-id="{{ $cv->id }}" 
                                    data-ten="{{ $cv->thuVienKPI->ten_kpi }}" 
                                    data-chitieu="{{ $cv->thuVienKPI->chi_tieu }} {{ $cv->thuVienKPI->don_vi }}"
                                >
                                    <i class="bi bi-send-fill"></i> Nộp báo cáo
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
                </div>
                
                <!--/ Projects table -->
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
                        <!-- <div class="col-12">
                            <label class="form-label fw-bold">Nội dung giải trình / Minh chứng</label>
                            <textarea name="noi_dung" class="form-control" rows="3" placeholder="Mô tả chi tiết công việc đã làm..."></textarea>
                        </div> -->

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
@push('script')
<script src="{{ asset('js/congvieccanhan.js') }}"></script>
@endpush
@endsection