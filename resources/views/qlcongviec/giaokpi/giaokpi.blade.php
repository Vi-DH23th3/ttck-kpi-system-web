<form action="{{route('manager.qlcongviec.giaokpi')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!--FORM GIAO KPI-->
            <div class="col-md-7">         
                <!-- CARD: THÔNG TIN KPI -->
                <div class="card shadow-sm mb-3">
                    
                        <div class="card-header fw-bold text-primary">
                            1. Thông tin KPI <span class="text-danger">*</span>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="session_index" name="session_index">
                            <!-- Tên KPI -->
                            <div class="mb-3">
                                <label class="small text-muted">Tên công việc (Chọn từ thư viện hoặc tự nhập mới)</label>
                                <input list="kpiTemplates" id="kpiSelector" class="form-control" placeholder="Gõ để tìm hoặc nhập tên mới..." value="{{ old('ten_kpi', $kpi->ten_kpi ?? '') }}">
                                <div id="kpi-datalist-container">
                                    <datalist id="kpiTemplates">
                                        @foreach($congviec as $cv)
                                            <option value="{{$cv->ten_kpi}}" data-id="{{ $cv->id }}" 
                                                    data-ten="{{$cv->ten_kpi}}"
                                                    data-iddm="{{$cv->dm_cv_id}}"
                                                    data-tendm="{{$cv->danhMuc->ten_cong_viec ?? ''}}"
                                                    data-chitieu="{{ $cv->chi_tieu }}" 
                                                    data-donvi="{{ $cv->don_vi }}" 
                                                    data-chuky="{{ $cv->chu_ky }}" data-ghichu="{{ $cv->ghi_chu }}" data-realname="{{$cv->ten_kpi}}">
                                                    Chỉ tiêu: {{$cv->chi_tieu}} {{$cv->don_vi}}
                                            </option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>

                            <!-- Chỉ tiêu -->
                            <div class="row">
                                <input type="hidden" name="ten_kpi" id="target_ten_kpi">
                                <input type="hidden" id="target_dmcv_id" name="dm_cv_id">
                                <input type="hidden" id="target_kpi_id" name="kpi_id">
                                
                                    <div id="tt_chi_tieu" class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Chỉ tiêu</label>
                                                <input type="number" name="chi_tieu" id="target_chi_tieu" class="form-control" value="{{ old('chi_tieu', $kpi->chi_tieu ?? '') }}" >
                                            </div>
                                            <div class="col-md-4">
                                                <label>Đơn vị</label>
                                                <input type="text" name="don_vi" id="target_don_vi" class="form-control" value="{{ old('don_vi', $kpi->don_vi ?? '') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Chu kỳ</label>
                                                <input type="text" name="chu_ky" id="target_chu_ky" class="form-control" value="{{ old('chu_ky', $kpi->chu_ky ?? '') }}">
                                            </div>
                                        </div>
                                    </div>

                            </div>
                            <div id="daChiTieu" class="d-none">
                                <label class="fw-bold mb-2" id="dkp">Danh sách chỉ tiêu thành phần</label>
    
                                <div id="dynamic-rules-container"></div>
                                <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="add-rule-btn">
                                    <i class="bi bi-plus-circle"></i> + Thêm chỉ tiêu mới
                                </button>
                            </div>
                            <!-- Danh mục -->
                            <div class="mb-3">
                                <label class="small text-muted">Danh mục công việc</label>
                                <input list="dmcvTemplates" name="ten_dmcv" id="dmcvSelector" class="form-control input-dmcv" value="{{ old('ten_dmcv', $kpi->danhMuc->ten_cong_viec ?? '') }}" >       
                            </div>
                            <!-- Bù nợ -->
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="cho_phep_bu" id="cho_phep_bu" {{ old('ten_dmcv') ? 'checked' : ''}}>
                                <label class="form-check-label">Cho phép bù nợ</label>
                            </div>

                            <div class="mt-2">
                                <input type="number" name="nguong_duoc_bu" class="form-control"
                                    placeholder="Ngưỡng tối thiểu (%)" value="{{ old('nguong_duoc_bu') }}">
                            </div>
                        </div>
                    </div>

                    <!-- CARD: LOẠI KPI -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header fw-bold text-primary">
                            2. Loại KPI <span class="text-danger">*</span> (Nếu loại theo chu kỳ, vui lòng điền thêm thông tin ở phần cấu hình nâng cao)
                        </div>
                        <div class="card-body">

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="loai_kpi" value="don_gian" {{ (old('loai_kpi', 'don_gian') == 'don_gian') ? 'checked' : '' }}>
                                <label class="form-check-label text-primary">Đơn giản (chỉ cần hoàn thành chỉ tiêu)</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="loai_kpi" value="nang_cao" {{ old('loai_kpi') == 'nang_cao' ? 'checked' : ''}}>
                                <label class="form-check-label">Theo chu kỳ</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="loai_kpi" id="manyKPI" value="da_chi_tieu" {{ old('loai_kpi') == 'da_chi_tieu' ? 'checked' : ''}}>
                                <label class="form-check-label" for="">Nhiều chỉ tiêu</label>
                            </div>
                        </div>
                    </div>

                    <!-- CARD: CẤU HÌNH NÂNG CAO -->
                    <div class="card shadow-sm mb-3 d-none" id="chuKyKPI">
                        <div class="card-header fw-bold text-primary">
                            Thiết lập chu kỳ
                        </div>

                        <div class="card-body">

                            <!-- Tần suất -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Số lần tối thiểu</label>
                                    <input type="number" name="so_lan_toi_thieu_thang" class="form-control" value="{{ old('so_lan_toi_thieu_thang') }}">
                                </div>
                                <div class="col-md-6">
                                    <label>Chu kỳ (tháng)</label>
                                    <input type="number" name="chu_ky_thang" class="form-control" value="{{ old('chu_ky_thang') }}">
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- CARD: THỜI GIAN -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header fw-bold text-primary">
                            3. Thời gian <span class="text-danger">*</span>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small text-muted">Ngày bắt đầu</label>
                                    <input type="date" name="ngay_bat_dau" class="form-control" value="{{ old("ngay_bat_dau", ($namhoc ? $namhoc->ngay_bat_dau : date('Y-m-d'))) }}" >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small text-muted">Ngày kết thúc (Deadline)</label>
                                    <input type="date" name="ngay_ket_thuc" class="form-control border-danger" value="{{ old("ngay_ket_thuc", ($namhoc ? $namhoc->ngay_ket_thuc : date('Y-m-d'))) }}" >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD: KHÁC -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header fw-bold text-primary">
                            4. Khác
                        </div>

                        <div class="card-body">
                            <div class="mb-2">
                                <label>Mức độ ưu tiên<span class="text-danger">*</span></label>
                                <select name="muc_do" class="form-select">
                                    <option value="1" {{old('muc_do') == 1 ? 'checked' : ''}}>Thấp</option>
                                    <option value="2" {{old('muc_do') == 2 ? 'checked' : ''}}>Trung bình</option>
                                    <option value="3" {{old('muc_do') == 3 ? 'checked' : ''}}>Cao</option>
                                </select>
                            </div>

                            <div>
                                <label>Ghi chú</label>
                                <textarea name="ghi_chu" class="form-control" id="ghi_chu" value ="{{old('ghi_chu')}}"></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="btn-review" class="btn btn-primary">
                        Xem review
                    </button>
                </div>

                <!-- RIGHT -->
                <div class="col-md-5">

                    <!-- PREVIEW -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header fw-bold text-success">
                            <i class="bi bi-eye me-2"></i> Xem trước KPI
                        </div>
                    
                        <div class="card-body">
                            <div id="preview-section">
                                <h5 id="preview-ten_kpi"></h5>
                                <div id="tt_review">
                                    <p><b>Chỉ tiêu:</b> <span id="preview-chi_tieu"></span> </p>
                                    <p><b>Đơn vị tính:</b> <span id="preview-don_vi"></span></p>
                                    <p><b>Chu kỳ:</b> <span id="preview-chu_ky"></span></p>
                                </div>
                                <p><b>Loại KPI:</b> <span id="preview-loai_kpi"></span></p>

                                <div id="preview-rules"></div>

                                <p><b>Bắt đầu:</b> <span id="preview-ngay_bat_dau"></span></p>
                                <p><b>Kết thúc:</b> <span id="preview-ngay_ket_thuc"></span></p>
                                <p><b>Mức độ:</b> <span id="preview-muc_do"></span></p>
                                <p><b>Ghi chú:</b> <span id="preview-ghi_chu"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- CHỌN NGƯỜI -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header fw-bold text-primary">
                            <i class="bi bi-person me-2"></i> Người thực hiện <span class="text-danger">*</span>
                        </div>

                        <div class="card-body">
                            @include('qlcongviec.giaochitieu_usertable')
                        </div>
                    </div>

                    <!-- ACTION -->
                    <div class="text-end">
                        <button class="btn btn-secondary">Hủy</button>
                        <button type="button" class="btn btn-primary" onclick="xacNhan(this)" data-message="Xác nhận giao KPI này?">
                            <i class="bi bi-check2-circle me-2"></i>Xác nhận Giao việc
                        </button>
                    </div>

                </div>
            </div>
        <template id="rule-row-template">
            <div class="row g-1 mb-2 align-items-center rule-row border-bottom pb-2">
                <div class="col-3">
                    <input type="text" name="da_chi_tieu_ten[]" class="form-control form-control-sm" placeholder="Tên chỉ tiêu">
                </div>

                <div class="col-1">
                    <select name="toan_tu[]" class="form-select form-select-sm fw-bold">
                        <option value="=">=</option>
                        <option value=">=">≥</option>
                        <option value="<=">≤</option>
                    </select>
                </div>

                <div class="col-2">
                    <input type="number" name="da_chi_tieu_gia_tri[]" class="form-control form-control-sm" placeholder="Số">
                </div>
                <div class="col-2">
                    <input type="text" name="don_vi_dct[]" class="form-control form-control-sm" placeholder="hệ thống, yêu cầu,...">
                </div>

                <div class="col-1">
                    <input type="text" name="chu_ky_dct[]" class="form-control form-control-sm" placeholder="ngày/tháng...">
                </div>
                <div class="col-2">
                    <select name="pham_vi[]" class="form-select form-select-sm">
                        <option value="tat_ca">Lũy kế</option>
                        <option value="bao_cao">Trên mỗi báo cáo</option>
                    </select>
                </div>
      

                <div class="col-1 text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-rule border-0">
                        X
                    </button>
                </div>
            </div>
        </template>
</form>
