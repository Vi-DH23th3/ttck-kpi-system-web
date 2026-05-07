<div class="row g-4 mb-4 mt-2">
    @php
        $adminCards = [
            ['label' => 'Người dùng', 'val' => $tongUser, 'icon' => 'bi-people', 'color' => 'primary', 'route' => route('admin.users.index')],
            ['label' => 'Phòng ban', 'val' => $tongDonVi, 'icon' => 'bi-building', 'color' => 'success', 'route' => route('admin.donvi.index')],
            ['label' => 'Năm học', 'val' => $tongNamHoc, 'icon' => 'bi-calendar-check', 'color' => 'info', 'route' => route('admin.namhoc.index')],
            ['label' => 'Phân công KPI', 'val' => $tongPhanCong, 'icon' => 'bi-clipboard-data', 'color' => 'warning', 'route' => route('system.qlcongviec.index')],
        ];
    @endphp

    @foreach($adminCards as $card)
    <div class="col-md-3">
        <a href="{{ $card['route'] }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100 admin-card-hover" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-{{ $card['color'] }} bg-opacity-10 p-3">
                            <i class="bi {{ $card['icon'] }} fs-4 text-{{ $card['color'] }}"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="fw-bold mb-0 text-dark">{{ $card['val'] }}</h3>
                            <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">{{ $card['label'] }}</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-{{ $card['color'] }} small fw-bold">
                        Quản lý ngay <i class="bi bi-arrow-right ms-2"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>
<style>
    .admin-card-hover:hover {
        transition: 0.3s ease;
        transform: translateY(-5px);
        box-shadow: rgba(0,0,0,1);
    }
</style>