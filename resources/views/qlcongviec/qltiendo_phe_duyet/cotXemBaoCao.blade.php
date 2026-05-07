@if($baoCao)
    @php
        function toArray($data) {
            if (is_array($data)) return $data;
            if (is_string($data)) return json_decode($data, true) ?? [];
            return [];
        }
        $pc = $baoCao->chiTietPhanCong->phanCong;
        $rules = toArray($pc->dieu_kien_phu);
        $values = toArray($baoCao->gia_tri_thuc_te);
        $loai = $pc->loai_kpi ?? 'don_gian';

        $label = match($loai) {
            'da_chi_tieu' => 'Danh sách chỉ tiêu thành phần',
            'nang_cao' => 'Điều kiện phụ đi kèm',
            default => 'Thông tin KPI'
        };
    @endphp

    <div class="mb-3 p-2 border rounded bg-light">
        {{-- 1. Thông tin KPI --}}
        <div class="mb-2 d-flex justify-content-between align-items-start">
            <span>Tên KPI: <strong>{{ $pc->thuVienKPI->ten_kpi ?? 'N/A' }}</strong></span>
            <small class="text-muted">
                <i class="bi bi-calendar-event me-1"></i>{{ date('d/m/Y', strtotime( $baoCao->ngay_thuc_hien)) }}
            </small>
        </div>

        {{-- Phần hiển thị tiến độ --}}
        @if($loai === 'don_gian')
            <div class="row mb-3">
                <div class="col-6">
                    <small class="text-muted d-block">Báo cáo lần này:</small>
                    <strong class="text-primary fs-5">+ {{ $baoCao->tien_do_thuc }}</strong> 
                    <small>{{ $pc->thuVienKPI->don_vi ?? '' }}</small>
                </div>
                <div class="col-6 border-start">
                    <small class="text-muted d-block">Tiến độ lũy kế nếu duyệt:</small>
                    <strong class="text-dark fs-5">
                       {{ (float)$baoCao->chiTietPhanCong->thuc_te_dat_duoc + (float)$baoCao->tien_do_thuc }}
                    </strong> 
                    <small>/ {{ $chiTieuDG ?? 0 }}</small>
                </div>
            </div>
        @elseif($loai === 'nang_cao')
            @php
                if($baoCao->tien_do_thuc >= 100)
                    $tiendo = 1;
                else
                    $tiendo = 0;
            @endphp
            <div class="row mb-3">
                <div class="col-4">
                    <small class="text-muted d-block">Báo cáo lần này:</small>
                    <strong class="text-primary fs-5">+ {{ $tiendo }}</strong> 
                    <small>{{ $pc->thuVienKPI->don_vi ?? '' }}</small>
                </div>
                <div class="col-4 border-start">
                    <small class="text-muted d-block">
                        Hoàn thành: <span class="text-info d-block">{{ (float)$baoCao->tien_do_thuc }}%</span>
                    </small>
                       
                </div>
                <div class="col-4 border-start">
                    <small class="text-muted d-block">Tiến độ lũy kế nếu duyệt:</small>
                    <strong class="text-dark fs-5">
                       {{ (float)$baoCao->chiTietPhanCong->thuc_te_dat_duoc + (float)$tiendo }}
                    </strong> 
                    <small>/ {{ $pc->thuVienKPI->chi_tieu ?? 0 }}</small>
                </div>
            </div>
        @else
            @if(!empty($dieu_kien_phu))
                <div class="mt-2 p-3 border-start border-info bg-light-subtle rounded mb-3">
                    <strong class="small text-muted d-block mb-2">
                        <i class="bi bi-shield-check me-1"></i>{{$label}}
                    </strong>

                    @foreach($dieu_kien_phu as $rule)
                        @php
                            $actual = $values[$rule['key']] ?? 0;
                        @endphp
                        <div class="d-flex justify-content-between align-items-center small mb-2">
                            <span>
                                <i class="bi bi-dot"></i> {{ $rule['ten'] ?? 'N/A' }}:
                                <b class="{{ $rule['dat'] ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($rule['actual'] ?? 0) }}
                                </b>
                                / {{ number_format($rule['target'] ?? 0) }}
                            </span>

                            <span>
                                <i class="bi {{ $rule['dat'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' }}"></i>
                                <span class="badge {{ $rule['pham_vi'] === 'tat_ca' ? 'bg-info-subtle text-info' : 'bg-secondary-subtle text-secondary' }}">
                                    {{ $rule['pham_vi'] === 'tat_ca' ? 'LŨY KẾ' : 'KỲ NÀY' }}
                                </span>
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
        <hr>

        {{-- MINH CHỨNG --}}
        @if(!empty($baoCao->file_minh_chung))
            <div class="mb-3">
                <label class="small text-muted d-block mb-1">Minh chứng:</label>
                <div class="btn-group">
                    <a href="{{ asset('storage/' . $baoCao->file_minh_chung) }}" target="_blank" class="btn btn-sm btn-outline-info">Xem file</a>
                    <a href="{{ asset('storage/' . $baoCao->file_minh_chung) }}" download class="btn btn-sm btn-outline-primary">Tải về</a>
                </div>
            </div>
        @endif

        {{-- GHI CHÚ --}}
        <div>
            <label class="small text-muted">Ghi chú nhân viên:</label>
            <div class="p-2 border rounded bg-light small">
                {{ $baoCao->ghi_chu ?? 'Không có ghi chú' }}
            </div>
        </div>
    </div>
@else
    <div class="alert alert-warning">Không tìm thấy dữ liệu báo cáo.</div>
@endif
<hr>
    <div class="d-flex gap-1 justify-content-center">
        <form action="{{route('manager.qlcongviec.qltiendo.duyet')}}" method="POST">
            @csrf
            <input type="hidden" name="bao_cao_cong_viec_id" value="{{ $baoCao->id }}">
            <button type="button" class="btn btn-sm btn-primary" onclick="xacNhan(this)" data-message="Xác nhận duyệt báo cáo này?">Duyệt</button>
        </form>
        <button type="button" class="btn btn-sm btn-danger btn-tralaibc" data-tlid="{{ $baoCao->id }}">Từ chối</button>
    </div>
