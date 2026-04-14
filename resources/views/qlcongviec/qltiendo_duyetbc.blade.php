@extends('layouts.admin')
@section('title', 'Quản lý tiến độ & Duyệt báo cáo')
@section('content')
<div class="card-body fs-6">
    <div class=" border rounded">
        
        <div class="p-3 border bg-light shadow-sm p-4 rounded-2">
       <div class="d-flex justify-content-between align-items-center m-2">
            <h5 class="card-header fw-semibold">Danh sách công việc</h5>
            <form method="GET" class="d-inline-block w-auto border-0">
                <select name="trangthai" class="form-select shadow-sm fw-semibold text-secondary border-secondary-subtle"
                        style="font-size: 0.9rem; min-width: 200px;" onchange="this.form.submit()">
                    <option value="tat_ca" {{ request('trangthai') == 'tat_ca' ? 'selected' : '' }}> -- Tất cả -- </option>
                    <option value="da_hoan_thanh" {{ request('trangthai') == 'da_hoan_thanh' ? 'selected' : '' }}>Đã hoàn thành</option>
                    <option value="dang_thuc_hien" {{ request('trangthai') == 'dang_thuc_hien' ? 'selected' : '' }}>Đang thực hiện</option>
                    <option value="dang_no" {{ request('trangthai') == 'dang_no' ? 'selected' : '' }}>Đang nợ</option>
                </select>
            </form>
       </div>
        <div class=" mb-3 fw-semibold border rounded">
            <table class="table table-bordered table-hover bg-white">
    <thead class="table-light">
        <tr class="table-primary sticky-top" style="top: 80px; z-index: 10;">
            <th class="text-primary text-center">STT</th>
            <th class="text-primary">KPI / Nhân viên</th>
            <th class="text-primary text-center">Loại</th>
            <th class="text-primary">Tiến độ (Số lượng)</th>
            <th class="text-primary">% KPI</th>
            <th class="text-primary">% Thời gian</th>
            <th class="text-primary">Đánh giá</th>
            <th class="text-primary">Hạn (Deadline)</th>
            <th class="text-primary">Trạng thái</th>
            <th class="text-primary text-center">Báo cáo</th>
            <th class="text-primary text-center">Dự kiến</th>
            <th class="text-primary text-center">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($group as $tenDanhMuc => $list)
    <tr class="table-info">
        <td colspan="12"><strong>{{ $tenDanhMuc }}</strong></td>
    </tr>

        @foreach($list as $index => $dspc)

     
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                
                {{-- KPI & Nhân viên --}}
                <td>
                    <div class="fw-bold text-dark">{{ $dspc->ten_kpi }}</div>
                    <small class="text-muted"><i class="bi bi-people-fill me-1"></i>Nhân viên: {{ $dspc->ten_nv }}</small> <br>
                    @if($dspc->dieu_kien_phu != NULL)
                        <span class="fw-semibold small text-muted">Điều kiện: {{$dspc->dieu_kien_phu  }}</span>    <br>      
                    @endif
                    @if($dspc->so_lan_toi_thieu_thang)
                        <span class="small">Tần suất: {{ $dspc->so_lan_toi_thieu_thang }} lần / {{ $dspc->chu_ky_thang }} tháng</span> 
                    @endif
                    
                </td>

                {{-- Loại KPI --}}
                <td class="text-center">
                    @if($dspc->loai_kpi == 'don_gian')
                        <span class="badge bg-secondary">Đơn giản</span>
                    @else
                        <span class="badge bg-info">Nâng cao</span>          
                    @endif
                </td>

                {{-- Tiến độ số lượng --}}
                <td>
                     @if($dspc->so_lan_toi_thieu_thang != NULL)
                        <span class="fw-semibold text-info">{{ $dspc->so_thang_bao_cao }}</span>/{{ $dspc->so_thang_yeu_cau }} <small>tháng</small>
                    @else
                        <span class="fw-semibold text-success">{{ $dspc->thuc_te_dat_duoc }}</span>/{{ $dspc->thuVienKPI->chi_tieu }}                    
                    @endif
                </td>

                {{-- % KPI --}}
                <td style="min-width: 120px;">
                    <div class="progress" style="height: 18px;">
                        <div class="progress-bar bg-success progress-bar-striped" role="progressbar" style="width: {{ $dspc->tien_do }}%">
                            {{ $dspc->tien_do }}%
                        </div>
                    </div>
                    @if($dspc->so_lan_toi_thieu_thang != NULL && $dspc->thuc_te_dat_duoc > $dspc->so_thang_bao_cao)
                        <small class="text-info font-italic">* Có báo cáo dư trong tháng</small>
                    @endif
                </td>

                {{-- % Thời gian --}}
                <td style="min-width: 120px;">
                    <div class="progress" style="height: 18px;">
                        <div class="progress-bar {{ $dspc->tien_do_ngay > 100 ? 'bg-danger' : 'bg-warning text-dark' }}" role="progressbar" style="width: {{ $dspc->tien_do_ngay > 100 ? 100 : $dspc->tien_do_ngay }}%">
                            {{ round($dspc->tien_do_ngay, 1) }}%
                        </div>
                    </div>
                </td>

                {{-- Đánh giá --}}
                <td>
                    <span class="badge {{ $dspc->tien_do < $dspc->tien_do_ngay ? 'bg-outline-danger text-danger' : 'bg-outline-primary text-primary' }} border">
                        {{ $dspc->danh_gia }}
                    </span>
                </td>

                {{-- Deadline --}}
                <td>
                    <small class="text-muted">Hạn cuối: {{ date('d/m/Y', strtotime($dspc->ngay_ket_thuc)) }}</small>
                    <br>
                    <small class="fw-bold {{ str_contains($dspc->deadline, 'Quá hạn') ? 'text-danger' : 'text-success' }}">
                        {{ $dspc->deadline }}
                    </small>
                </td>
                <td>
                    @php
                                    $trang_thai = [
                                        'da_hoan_thanh'  => ['label' => 'Đã hoàn thành', 'class' => 'bg-success'],
                                        'dang_thuc_hien' => ['label' => 'Đang thực hiện', 'class' => 'bg-info'],
                                        'chua_dat'       => ['label' => 'Chưa đạt',       'class' => 'bg-danger'],
                                        'dang_no'        => ['label' => 'Đang nợ',        'class' => 'bg-warning'],
                                        'chua_bat_dau'   => ['label' => 'Chưa bắt đầu',   'class' => 'bg-secondary'],
                                    ];
                                    $currentStatus = $trang_thai[$dspc->trang_thai_tinh] ?? ['label' => $dspc->trang_thai_tinh, 'class' => 'bg-light text-dark'];
                                @endphp
                                <span class="badge {{ $currentStatus['class'] }} px-2 py-1">
                                    {{ $currentStatus['label'] }}
                                </span>
                </td>
                <td class="text-center">
                    @if($dspc->bao_cao_chua_duyet->isNotEmpty())
                        <button class="btn btn-xs btn-outline-primary btn-xem-bao-cao">
                            Xem báo cáo
                        </button>
                        <div class="d-none mt-2 text-start bao-cao-wrapper" id="file_minh_chung">
                            @foreach($dspc->bao_cao_chua_duyet as $bc)
                                @php
                                    $rules = is_array($dspc->dieu_kien_phu)? $dspc->dieu_kien_phu : json_decode($dspc->dieu_kien_phu, true) ?? [];
                                    $values = json_decode($bc->gia_tri_thuc_te, true) ?? [];
                                @endphp
                                <div class="mb-3 p-2 border rounded bg-light">
                                    {{-- 1. Tiến độ --}}
                                    <div class="mb-2">
                                        <strong>Tên KPI:</strong> {{ $bc->phanCong->thuVienKPI->ten_kpi }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Chỉ tiêu:</strong> {{ $bc->phanCong->thuVienKPI->chi_tieu}} / {{$bc->phanCong->thuVienKPI->don_vi }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Tiến độ thực hiện:</strong> {{ $bc->tien_do_thuc }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Ngày báo cáo:</strong> {{ $bc->ngay_thuc_hien }}
                                    </div>
                                    {{-- 2. Điều kiện phụ --}}
                                    @if(!empty($rules))
                                        <div class="mb-2">
                                            <strong>Điều kiện phụ:</strong>
                                            @foreach($rules as $key => $required)
                                                @php
                                                    $actual = $values[$key] ?? 0;
                                                    $dat = $actual >= $required;
                                                @endphp
                                                <div class="small">
                                                    {{ $key }}:
                                                    <b>{{ number_format($actual) }}</b>
                                                    / {{ number_format($required) }}
                                                    @if($dat)
                                                        <span class="text-success">✔</span>
                                                    @else
                                                        <span class="text-danger">❌</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- 3. File --}}
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="{{ asset('storage/' . $bc->file_minh_chung) }}" target="_blank"
                                        class="btn btn-xs btn-outline-info">
                                            Xem file
                                        </a>
                                        <a href="{{ asset('storage/' . $bc->file_minh_chung) }}" download
                                        class="btn btn-xs btn-outline-primary">
                                            Tải
                                        </a>
                                    </div>
                                    {{-- 4. Ghi chú --}}
                                    <div>
                                        <label class="small text-muted">Ghi chú:</label>
                                        <textarea class="form-control form-control-sm" rows="2" readonly>
                                            {{ $bc->ghi_chu ?? 'Không có' }}
                                        </textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <span class="text-muted small">Không có</span>
                    @endif
                </td>
                {{-- Tiến độ dự kiến --}}
                <td class="text-center fw-bold text-info">
                    {{ $dspc->tien_do_du_kien }}%
                </td>

                {{-- Hành động --}}
                <td class="text-center">
                
                    @if($dspc->bao_cao_chua_duyet->isNotEmpty())
                        @php $firstPending = $dspc->bao_cao_chua_duyet->first(); @endphp
                        <div class="d-flex gap-1 justify-content-center">
                            <form action="{{route('qlcongviec.qltiendo.duyet')}}" method="POST">
                                @csrf
                                <input type="hidden" name="bao_cao_cong_viec_id" value="{{ $firstPending->id }}">
                                <button type="submit" class="btn btn-sm btn-primary">Duyệt</button>
                            </form>
                            <button type="button" class="btn btn-sm btn-danger btn-tralaibc" data-tlid="{{ $firstPending->id }}">
                                Trả lại
                            </button>
                        </div>
                    @else
                        @if($dspc->trang_thai == 'da_hoan_thanh')
                            <span class="text-success small"><i class="fas fa-check-double"></i> Xong</span>
                        @else
                            <span class="text-muted small">---</span>
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach 
        @endforeach
    </tbody>
</table>
        </div>
        </div>
    </div>
</div>     
    
<div class="modal fade" id="modalXemBaoCao" tabindex="-1" aria-labelledby="modalXemBaoCaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalXemBaoCaoLabel"><i class="bi bi-file-earmark-arrow-up me-2"></i>Xem Báo Cáo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body body-xem-bao-cao">
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>  
<div class="modal fade" id="modalTraLai" tabindex="-1" aria-labelledby="modalTraLaiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalTraLaiLabel"><i class="bi bi-file-earmark-arrow-up me-2"></i>Trả lại</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body body-tra-lai">
                <div class="mb-3">
                    <label for="ghi_chu_tl" class="form-label">Lý do trả lại</label>
                </div>
                <form action="{{route('qlcongviec.qltiendo.tralai')}}" method="POST">
                    @csrf
                    <input type="hidden" name="bao_cao_cong_viec_id" class="tlbc">
                    <textarea class="form-control" rows="3" name="ghi_chu_tl"></textarea>
                    <button type="submit" class="btn btn-danger" >
                    Trả lại
                    </button>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div> 
@push('script')
<script src="{{ asset('js/baocao.js') }}"></script>                     
@endpush
@endsection