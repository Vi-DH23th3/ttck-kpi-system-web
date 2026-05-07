<div class="card h-100 shadow-sm border-0 bg-white" style="border-radius: 15px;">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-4" style="color: #2c3e50;">
            <i class="bi bi-exclamation-octagon-fill me-2" style="color: #e74a3b;"></i>KPI Cần Xử Lý Gấp
        </h6>
        <div class="list-group list-group-flush">
            @forelse($dsSapHetHan as $kpi)
            <div class="list-group-item px-0 border-0 mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div style="max-width: 70%;">
                        <div class="small fw-bold text-dark text-truncate">{{ $kpi->ten_kpi }}</div>
                        <small class="text-muted">{{ $kpi->ten_nv }}</small>
                    </div>
                    <span class="badge bg-danger-soft text-danger small">
                        <i class="far fa-clock me-1"></i>{{ date('d/m/Y',strtotime($kpi->ngay_ket_thuc)) }}
                    </span>
                </div>
                <div class="progress mt-2" style="height: 4px;">
                    <div class="progress-bar bg-danger" style="width: {{ $kpi->tien_do }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-muted small text-center">Tạm thời chưa có KPI nào sắp hạn</p>
            @endforelse
        </div>
    </div>
</div>