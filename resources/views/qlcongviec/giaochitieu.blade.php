@extends('layouts.admin')
@section('title', 'Giao việc')

@section('content')
 <div class="container-fluid py-4 row justify-content-center">
    <div class="col-lg-10 card shadow-sm">
        <div class="card-header bg-secondary text-white py-3">
            <h5 class="card-title mb-0"><i class="bi bi-person-plus-fill me-2"></i>Giao Chỉ Tiêu KPI Mới</h5>
        </div>
        <div class="card-body p-4">     
        <div class="row">
            <div class="col-md-5 border-end">
                <label class="form-label fw-bold">1. Chọn người thực hiện</label>
                <div class="input-group mb-2 ">
                    <button id="search-user-chitieu" type="submit" class="border-0 bg-white"><span class="input-group-text"><i class="bi bi-search"></i></span></button>
                    <input type="text" id="userSearch" class="form-control form-control-sm" placeholder="Tìm tên nhân viên..." value="{{request('search')}}">
                </div>
            <form action="{{route('qlcongviec.giaokpi')}}" method="POST">
                @csrf
                        @include('qlcongviec.giaochitieu_usertable')
                        <div class="form-text mt-2 text-primary italic">* Có thể chọn nhiều người để giao cùng một việc.</div>
                    </div>

                    <div class="col-md-7 ps-md-4">
                        <label class="form-label fw-bold">2. Thông tin chỉ tiêu</label>
                        
                        @if(request()->filled('kpi_id'))
                        @foreach($congviec as $cv)
                        <div class="mb-3 dmcv-tutvkpi">
                                <label class="small text-muted">Danh mục công việc</label>
                                <!-- <input type="hidden" value=" {{$cv->dm_cv_id}}" name="dmcv_id"> -->
                                <input type="text" name="ten_dmcv" class="form-control" value=" {{$cv->danhMuc->ten_cong_viec}}">
                        </div>
                        <div class="mb-3 kpi-tutvkpi">
                                <label class="small text-muted">Tên công việc</label>
                                <!-- <input type="hidden" value=" {{$cv->id}}" name="kpi_id"> -->
                                <input type="text" name="ten_kpi" class="form-control" value=" {{$cv->ten_kpi}}">
                        </div>
                        @endforeach
                        @else
                        <div class="mb-3" id="dmcv-template">
                            <label class="small text-muted">Danh mục công việc</label>
                            <input list="dmcvTemplates" name="ten_dmcv" id="dmcvSelector" class="form-control input-dmcv" placeholder="Gõ để tìm hoặc nhập tên mới..." required >
                        
                            <datalist id="dmcvTemplates">
                                @foreach($dmcongviec as $dmcv)
                                    <option value="{{$dmcv->ten_cong_viec}}| #{{$dmcv->id}}" data-dmcv="{{$dmcv->id}}" data-realname="{{$dmcv->ten_cong_viec}}" class="dmcv">
                                        {{ $dmcv->thu_vien_k_p_i_count ?? 0 }} KPI mẫu có sẵn
                                            <!-- {{$dmcv->ten_cong_viec}} - {{ $dmcv->thu_vien_k_p_i_count ?? 0 }} KPI mẫu -->
                                    </option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="mb-3" id="kpi-template">
                            <label class="small text-muted">Tên công việc (Chọn từ thư viện hoặc tự nhập mới)</label>
                            <input type="hidden" name="ten_kpi" id="target_ten_kpi">
                            <input list="kpiTemplates" id="kpiSelector" class="form-control" placeholder="Gõ để tìm hoặc nhập tên mới..." required >
                            <div id="kpi-datalist-container">
                                <datalist id="kpiTemplates">
                                    @foreach($congviec as $cv)
                                        <option value="{{$cv->ten_kpi}} | #{{$cv->id}}" data-id="{{ $cv->id }}" 
                                                data-chitieu="{{ $cv->chi_tieu }}" 
                                                data-donvi="{{ $cv->don_vi }}" 
                                                data-chuky="{{ $cv->chu_ky }}" data-realname="{{$cv->ten_kpi}}">
                                                id: {{$cv->id}} Chỉ tiêu: {{$cv->chi_tieu}} {{$cv->don_vi}}
                                        </option>
                                    @endforeach
                                </datalist>
                            </div>
                            
                        </div>
                        @endif
                        <div class="row">
                            <input type="hidden" name="kpi_id" id="target_kpi_id" value="{{ request('kpi_id') ? $cv->id  : ''}}">
                            <input type="hidden" name="dmcv_id" id="target_dmcv_id" value="{{ request('kpi_id') ? $cv->dm_cv_id  : ''}}">
                            <div class="col-md-3 mb-3">
                                <label class="small text-muted">Chỉ tiêu</label>
                                <input type="text" name="chi_tieu" class="form-control" id="target_chi_tieu" value="{{ request('kpi_id') == $cv->id ? $cv->chi_tieu : ''}}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="small text-muted">Đơn vị tính</label>
                                <input type="text" name="don_vi" class="form-control" id="target_don_vi" value="{{request('kpi_id') == $cv->id ? $cv->don_vi : '' }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="small text-muted">Chu kỳ</label>
                                <input type="text" name="chu_ky" class="form-control" id="target_chu_ky" value="{{request('kpi_id') == $cv->id ? $cv->chu_ky : ''}}">
                            </div>
                            <div class="col-md-3 mb-3 dmcv-phongban d-none">
                                <label class="small text-muted">Phòng ban</label>
                                <select name="donvi_id" id="" class="form-control">
                                    @foreach($dsdonvi as $dv)
                                    <option value="{{$dv->id}}">{{$dv->ten_don_vi}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted">Ngày bắt đầu</label>
                                <input type="date" name="ngay_bat_dau" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted">Ngày kết thúc (Deadline)</label>
                                <input type="date" name="ngay_ket_thuc" class="form-control border-danger" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted">Mức độ ưu tiên</label>
                                <select name="muc_do" class="form-select">
                                    <option value="1">Thấp</option>
                                    <option value="2" selected>Trung bình</option>
                                    <option value="3">Cao / Gấp</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted">Năm học</label>
                                <select name="namhoc_id" id="" class="form-control">
                                    @foreach($namhoc as $nh)
                                    <option value="{{$nh->id}}" {{ $cv->nam_hoc_id == $nh->id ? 'selected' : '' }}>{{$nh->ten_nam_hoc}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="small text-muted">Ghi chú hướng dẫn (nếu có)</label>
                            <textarea name="ghi_chu" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check2-circle me-2"></i>Xác nhận Giao việc
                            </button>
                            <a href="#" class="btn btn-link btn-sm text-secondary">Hủy bỏ</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('script')
  <script src="{{ asset('js/giaochitieu.js') }}"></script>
@endpush
@endsection