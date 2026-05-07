<div class="card h-100 shadow-sm border-0 bg-white" style="border-radius: 15px;">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-4" style="color: #2c3e50;">
            <i class="bi bi-trophy-fill me-2" style="color: #f6c23e;"></i>Top 5 Nhân viên Xuất sắc
        </h6>
        <div class="list-group list-group-flush">
            @forelse($topNhanVien as $nv)
            <div class="list-group-item d-flex justify-content-between align-items-center px-0 border-0 mb-2">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        @if(!empty($nv['avatar']))
                            {{-- Hiển thị ảnh nếu có --}}
                            <img src="{{ asset('storage/' . $nv['avatar']) }}" 
                                class="rounded-circle shadow-sm" 
                                style="width: 40px; height: 40px; object-fit: cover;"
                                alt="{{ $nv['ten_nv'] }}">
                        @else
                            {{-- Hiển thị chữ cái đầu nếu không có ảnh --}}
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" 
                                style="width: 40px; height: 40px; font-weight: bold; font-size: 0.9rem; text-transform: uppercase;">
                                {{ substr($nv['ten_nv'] ?? 'A', 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <span class="small fw-semibold text-dark">{{ $nv['ten_nv'] }}</span>
                </div>
                <span class="badge bg-success-soft text-success rounded-pill px-3">{{ $nv['so_luong'] }} KPI</span>
            </div>
            @empty
            <p class="text-muted small text-center">Chưa có dữ liệu hoàn thành</p>
            @endforelse
        </div>
    </div>
</div>