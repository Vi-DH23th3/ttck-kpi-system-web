@extends('layouts.admin')
@section('title', 'Chỉnh sửa Phân Công')

@section('content')
<div class="container-fluid mt-4">
    <form action="{{route('manager.phancong.update', $phanCong->id)}}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Thông tin Phân Công: {{ $phanCong->thuVienKPI->ten_kpi }}</h5>
                        <input type="hidden" name="ten_kpi" value="{{ $phanCong->thuVienKPI->ten_kpi }}">
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Loại KPI</label>
                                <select name="loai_kpi" class="form-select" id="loai_kpi">
                                    <option value="don_gian" {{ $phanCong->loai_kpi == 'don_gian' ? 'selected' : '' }}>Đơn giản</option>
                                    <option value="nang_cao" {{ $phanCong->loai_kpi == 'nang_cao' ? 'selected' : '' }}>KPI theo chu kì</option>
                                    <option value="da_chi_tieu" {{ $phanCong->loai_kpi == 'da_chi_tieu' ? 'selected' : '' }}>Đa chỉ tiêu</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                @php
                                    if($phanCong->trang_thai === 'chua_thuc_hien')
                                        $tt = 'Chưa thực hiện';
                                    elseif ($phanCong->trang_thai === 'dang_thuc_hien')
                                        $tt = 'Đang thực hiện';
                                    elseif ($phanCong->trang_thai === 'dang_no')
                                        $tt = 'Đang nợ';
                                    else
                                        $tt = 'Chưa đạt'
                                @endphp
                                <label class="form-label fw-bold">Trạng thái</label>
                                <input type="text" class="form-control" value="{{ $tt }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Chỉ tiêu</label>
                                <input name="chi_tieu" class="form-control mb-2"
                                    value="{{ $phanCong->thuVienKPI->chi_tieu }}">
                        
                            </div>
                            <div class="col-md-4">
                                <label>Đơn vị</label>
                                <input name="don_vi" class="form-control mb-2"
                                    value="{{ $phanCong->thuVienKPI->don_vi }}">
                        
                            </div>
                            <div class="col-md-4">
                                    <label>Chu kỳ</label>
                                    <input name="chu_ky" class="form-control"
                                        value="{{ $phanCong->thuVienKPI->chu_ky }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ngày bắt đầu</label>
                                <input type="date" name="ngay_bat_dau" class="form-control" value="{{ $phanCong->ngay_bat_dau}}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ngày kết thúc</label>
                                <input type="date" name="ngay_ket_thuc" class="form-control" value="{{ $phanCong->ngay_ket_thuc }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mức độ ưu tiên</label>
                            <select name="muc_do" class="form-select">
                                <option value="1" {{ $phanCong->muc_do_uu_tien == 1 ? 'selected' : '' }}>Thấp</option>
                                <option value="2" {{ $phanCong->muc_do_uu_tien == 2 ? 'selected' : '' }}>Trung bình</option>
                                <option value="3" {{ $phanCong->muc_do_uu_tien == 3 ? 'selected' : '' }}>Cao</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control">{{ $phanCong->ghi_chu }}</textarea>
                        </div>
                        <hr>
                        <div id="section_nang_cao" style="{{ $phanCong->loai_kpi == 'nang_cao' ? '' : 'display:none' }}">
                            <h6 class="fw-bold text-primary">Cấu hình Chu kỳ</h6>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Số lần tối thiểu</label>
                                    <input type="number" name="so_lan_toi_thieu_thang" class="form-control" value="{{ $phanCong->so_lan_toi_thieu_thang ?? 0 }}">
                                </div>
                                <div class="col-md-6">
                                    <label>Chu kỳ (tháng)</label>
                                    <input type="number" name="chu_ky_thang" class="form-control" value="{{ $phanCong->chu_ky_thang ?? 0 }}">
                                </div>
                            </div>
                        </div>
                        <div id="section_da_chi_tieu" style="{{ $phanCong->loai_kpi == 'da_chi_tieu' ? '' : 'display:none' }}">
                            <h6 class="fw-bold text-primary">Cấu hình Đa chỉ tiêu</h6>
                            <div id="wrapper_da_chi_tieu">
                                @if($phanCong->dieu_kien_phu)
                                    @foreach($phanCong->dieu_kien_phu as $index => $dk)
                                        <div class="row g-2 mb-2 border p-2 rounded">
                                            <div class="col-md-3">
                                                <input type="text" name="dieu_kien[{{$index}}][ten]" class="form-control form-control-sm" placeholder="Tên (VD: Email)" value="{{ $dk['ten'] ?? '' }}">
                                            </div>
                                            <div class="col-md-2">
                                                <select name="dieu_kien[{{$index}}][pham_vi]" class="form-select form-select-sm">
                                                    <option value="tat_ca" {{ $dk['pham_vi'] == 'tat_ca' ? 'selected' : '' }}>Lũy kế</option>
                                                    <option value="bao_cao" {{ $dk['pham_vi'] == 'bao_cao' ? 'selected' : '' }}>Mỗi BC</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <input type="text" name="dieu_kien[{{$index}}][toan_tu]" class="form-control form-control-sm" placeholder=">=" value="{{ $dk['toan_tu'] ?? '>=' }}">
                                            </div>
                                            <div class="col-md-1">
                                                <input type="number" name="dieu_kien[{{$index}}][gia_tri]" class="form-control form-control-sm" placeholder="Giá trị" value="{{ $dk['gia_tri'] ?? 0 }}">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" name="dieu_kien[{{$index}}][don_vi]" class="form-control form-control-sm" value="{{ $dk['don_vi'] ?? '' }}">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" name="dieu_kien[{{$index}}][chu_ky]" class="form-control form-control-sm" value="{{ $dk['chu_ky'] ?? '' }}">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-dct border-0">X </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="btn-add-dct">+ Thêm điều kiện</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-dark text-white font-weight-bold">Phân bổ & Chế độ bù</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Người được giao</label>
                            <!-- <select name="user_ids[]" class="form-select select2" multiple>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ in_array($user->id, $phanCong->chiTietPhanCong->pluck('user_id')->toArray()) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select> -->
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th width="40" class="text-center">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                                
                                            </th>
                                            <th>Họ tên</th>
                                        </tr>
                                    </thead>
                                    <tbody id="userTable">
                                        @foreach($users as $user)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" name="user_ids[]" value="{{$user->id}}" class="form-check-input user-checkbox"
                                                {{ in_array($user->id, $phanCong->chiTietPhanCong->pluck('user_id')->toArray()) ? 'checked' : '' }}>
                                                
                                            </td>
                                            <td>{{$user->name}} <small class="text-muted">({{$user->donVi->ten_don_vi ?? 'N/A'}})</small></td>
                                            <!-- <p>{{$user->name}}</p> -->
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                           </div>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="cho_phep_bu" id="cho_phep_bu" {{ $phanCong->cho_phep_bu ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="cho_phep_bu">Cho phép bù KPI</label>
                        </div>

                        <div id="nguong_bu_wrapper" style="{{ $phanCong->cho_phep_bu ? '' : 'display:none' }}">
                            <label class="form-label small">Ngưỡng tối thiểu được bù (%)</label>
                            <input type="number" name="nguong_duoc_bu" class="form-control" value="{{ $phanCong->nguong_duoc_bu ?? 0 }}">
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success" onclick="xacNhan(this)" data-message="Xác nhận cập nhật phân công?">Cập nhật Phân Công</button>
                    <a href="{{ route('manager.phancong') }}" class="btn btn-outline-secondary">Quay lại</a>
                </div>
            </div>
        </div>
    </form>
</div>

<template id="da-chi-tieu-template" >
    <div class="row g-2 mb-2 border p-2 rounded">
        <div class="col-3">
            <input type="text" name="da_chi_tieu_ten[]" class="form-control form-control-sm" placeholder="Tên chỉ tiêu">
        </div>
        <div class="col-2">
            <select name="pham_vi[]" class="form-select form-select-sm">
                <option value="tat_ca">Lũy kế</option>
                <option value="bao_cao">Trên mỗi báo cáo</option>
            </select>
        </div>
        <div class="col-1">
            <select name="toan_tu[]" class="form-select form-select-sm fw-bold">
                <option value="=">=</option>
                <option value=">=">≥</option>
                <option value="<=">≤</option>
            </select>
        </div>

        <div class="col-1">
            <input type="number" name="da_chi_tieu_gia_tri[]" class="form-control form-control-sm" placeholder="Số">
        </div>

        
        <div class="col-2">
            <input type="number" name="don_vi[]" class="form-control form-control-sm" placeholder="hệ thống, yêu cầu,...">
        </div>

        <div class="col-2">
            <input type="number" name="chu_ky[]" class="form-control form-control-sm" placeholder="ngày/tháng...">
        </div>

        <div class="col-1 text-start">
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-dct border-0">
                X
            </button>
        </div>
    </div>
</template>

  @push('script')
    <script src="{{ asset('js/thongbaoxacnhan.js') }}"></script>
    <script src="{{ asset('js/phancong.js') }}"></script>
  @endpush
@endsection