<div class="card h-100 shadow-sm border-0 bg-white" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 py-4">
        <h6 class="mb-0 fw-bold text-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            KPI cần xử lý theo nhân viên
        </h6>
    </div>

    <div class="card-body p-2" style="max-height: 400px; overflow-y: auto;">
        @foreach($group->take(6) as $tk)
            @php
                $bg_MacDinh = 'success'; 
                if ( str_contains($tk->canh_bao, 'Chưa đạt chu kỳ') || 
                    str_contains($tk->canh_bao, 'Chưa đạt đa chỉ tiêu') || 
                    str_contains($tk->canh_bao, 'Quá hạn')) {
                    
                    $bg_MacDinh = 'danger'; 
                    
                } elseif (
                        str_contains($tk->canh_bao, 'Thiếu chu kỳ') || 
                        str_contains($tk->canh_bao, 'Sắp hết hạn') || 
                        str_contains($tk->canh_bao, 'Đang đúng tiến độ')|| 
                        str_contains($tk->canh_bao, 'Đạt ngưỡng bù')) {
                    $bg_MacDinh = 'warning text-dark'; 
                } elseif (str_contains($tk->canh_bao, 'Chưa có dữ liệu') || 
                        str_contains($tk->canh_bao, 'Chưa bắt đầu')) {
                    
                    $bg_MacDinh = 'secondary'; 
                }
            @endphp

            <div class="border rounded-3 p-2 mb-2 bg-light">
                <!-- KPI -->
                <div class="fw-semibold small text-truncate">
                    {{ $tk->phanCong->thuVienKPI->ten_kpi }}
                </div>

                <!-- Nhân viên -->
                <div class="small text-muted">
                    {{ $tk->ten_nv }}
                </div>

                <!-- Progress -->
                <div class="small">
                    <span class="fw-bold text-success">
                        {{ $tk->thuc_te_dat_duoc }}
                    </span>
                    / {{ $tk->phanCong->thuVienKPI->chi_tieu }}
                </div>

                <!-- Status + Warning -->
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="badge bg-{{ $bg_MacDinh }}" style="white-space: pre-line;">
                        {{ $tk->canh_bao }}
                    </span>

                    <small class="text-muted">
                        {{ date('d/m', strtotime($tk->ngay_ket_thuc)) }}
                    </small>
                </div>
            </div>
        @endforeach
    </div>
</div>

                          
                               