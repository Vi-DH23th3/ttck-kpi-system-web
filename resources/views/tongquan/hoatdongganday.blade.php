<div class="card h-100 shadow-sm border-0 bg-white" style="border-radius: 15px;">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-4" style="color: #2c3e50;">
            <i class="bi bi-clock-history me-2" style="color: #36b9cc;"></i>Cập nhật gần đây
        </h6>
        <div class="list-group list-group-flush">
            @foreach($hoatDongGanDay as $hdgd)
            <div class="list-group-item px-0 border-0 mb-3">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        @if($hdgd->user && $hdgd->user->avatar)
                            {{-- Hiển thị ảnh nếu có --}}
                            <img src="{{ asset('storage/' . $hdgd->user->avatar) }}" 
                                class="rounded-circle shadow-sm" 
                                style="width: 40px; height: 40px; object-fit: cover;"
                                alt="{{ $hdgd->ten_nv }}">
                        @else
                            {{-- Hiển thị chữ cái đầu nếu không có ảnh --}}
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" 
                                style="width: 40px; height: 40px; font-weight: bold; font-size: 0.9rem; text-transform: uppercase;">
                                {{ substr($hdgd->ten_nv ?? 'A', 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="small mb-0 text-dark">
                            <strong>{{ $hdgd->user->name }}</strong> đã cập nhật tiến độ 
                            <strong>{{ $hdgd->chiTietPhanCong->phanCong->thuVienKPI->ten_kpi }}</strong>
                        </p>
                        <small class="text-muted" style="font-size: 0.7rem;">
                            {{ $hdgd->updated_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>