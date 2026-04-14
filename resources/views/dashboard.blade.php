@extends('layouts.admin')
@section('title', 'Tổng quan KPI')

@section('content')
<div class="container-fluid py-4" style="background-color: #bfdbf0; min-height: 100vh;">

    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-body">
            <form id="mainForm" action="{{ route('dashboard') }}" method="GET" class="row align-items-end g-3">
                @csrf
                <div class="col-md-3">
                    <label class="form-label fw-bold text-secondary small">Năm học</label>
                    <select name="filter_nh" class="form-select border-2" onchange="this.form.submit()">
                        @foreach($namhoc as $nh)
                            <option value="{{$nh->id}}" {{ request('filter_nh') == $nh->id ? 'selected' : '' }}>{{$nh->ten_nam_hoc}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold text-secondary small">Phòng ban</label>
                    <select name="filter_pb" class="form-select border-2" onchange="this.form.submit()">
                        <option value="all">--- Tất cả phòng ban ---</option>
                        @foreach($phong as $pb)
                            <option value="{{$pb->id}}" {{ request('filter_pb') == $pb->id ? 'selected' : '' }}>{{$pb->ten_don_vi}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold text-secondary small">Nhân viên</label>
                    <select class="form-select border-2" name="filter_nv" onchange="this.form.submit()">
                        <option value="all">Tất cả</option>
                        @foreach($user as $u)   
                            <option value="{{$u->id}}" {{ request('filter_nv') == $u->id ? 'selected' : '' }}>{{$u->name}}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3 text-end">
                    <button type="button" onclick="submitExport()" class="btn btn-success">
                        <i class="bi bi-box-arrow-up-right"></i> Xuất Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4 text-center">
        @php
            $displayCards = [
                ['label' => 'Tổng KPI', 'val' => $tong, 'color' => '#007bff'],
                ['label' => 'Hoàn thành', 'val' => $hoanthanh, 'color' => '#28a745'],
                ['label' => 'Chưa đạt', 'val' => $chuadat, 'color' => '#6c757d'],
                ['label' => 'Đang thực hiện', 'val' => $dangthuchien, 'color' => '#17a2b8'],
                ['label' => 'Sắp hết hạn', 'val' => $saphethan, 'color' => '#ffc107'],
                ['label' => 'Quá hạn', 'val' => $quahan, 'color' => '#dc3545'],
            ];
        @endphp

        @foreach($displayCards as $item)
        <div class="col-md-2 col-6">
            <div class="card h-100 shadow-sm border-0" style="border-top: 5px solid {{ $item['color'] }} !important; border-radius: 10px;">
                <div class="card-body p-3">
                    <p class="small fw-bold text-uppercase text-muted mb-1">{{ $item['label'] }}</p>
                    <h3 class="fw-bold mb-0" style="color: {{ $item['color'] }}">{{ $item['val'] }}</h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body">
            <h5 class="card-title fw-bold text-dark mb-3"><i class="fas fa-chart-bar me-2"></i>Thống kê báo cáo theo tháng</h5>
            <div style="height: 300px; width: 100%;">
                <canvas id="kpiChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list-ul me-2"></i>Danh sách chi tiết KPI</h5>
        </div>
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                    <tr>
                        <th class="ps-4">KPI</th>
                        <th>Nhân viên</th>
                        <th>Tiến độ</th>
                        <th width="150">Tỷ lệ %</th>
                        <th>Trạng thái</th>
                        <th>Cảnh báo</th>
                        <th class="pe-4 text-center">Hạn (Deadline)</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($group as $tenDanhMuc => $list)
                    <tr style="background-color: rgba(0, 123, 255, 0.05);">
                        <td colspan="7" class="ps-4 text-primary fw-bold text-uppercase small">
                            <i class="fas fa-tag me-1"></i> {{ $tenDanhMuc }}
                        </td>
                    </tr>
                        @foreach($list as $tk)
                        <tr>
                            <td class="ps-4 fw-semibold">{{$tk->ten_kpi}}</td>
                            <td>{{$tk->ten_nv}}</td>
                            <td>
                                @if($tk->so_lan_toi_thieu_thang != NULL)
                                    <span class="text-info fw-bold">{{ $tk->so_thang_bao_cao }}</span>/{{ $tk->so_thang_yeu_cau }} <small>tháng</small>
                                @else
                                    <span class="text-success fw-bold">{{ $tk->thuc_te_dat_duoc }}</span>/{{ $tk->thuVienKPI->chi_tieu }}
                                @endif
                            </td>
                            <td>
                                <div class="progress" style="height: 10px; border-radius: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $tk->tien_do }}%">
                                        <small>{{ $tk->tien_do }}%</small>
                                    </div>
                                </div>
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
                                    $currentStatus = $trang_thai[$tk->trang_thai_tinh] ?? ['label' => $tk->trang_thai_tinh, 'class' => 'bg-light text-dark'];
                                @endphp
                                <span class="badge {{ $currentStatus['class'] }} px-2 py-1">
                                    {{ $currentStatus['label'] }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $bg_MacDinh = 'success'; 
                                    if (str_contains($tk->canh_bao, 'Không đủ tần suất') || str_contains($tk->canh_bao, 'ĐK phụ chưa đạt')) {
                                        $bg_MacDinh = 'danger'; 
                                    } elseif (str_contains($tk->canh_bao, 'Sắp hết hạn') || str_contains($tk->canh_bao, 'Đang trong chu kỳ')) {
                                        $bg_MacDinh = 'warning text-dark'; 
                                    } elseif (str_contains($tk->canh_bao, 'Chưa có dữ liệu') || str_contains($tk->canh_bao, 'Chưa bắt đầu')) {
                                        $bg_MacDinh = 'secondary'; 
                                    }
                                @endphp
                                <span class="badge bg-{{ $bg_MacDinh }} shadow-sm">
                                    {{ $tk->canh_bao }}
                                </span>
                            </td>
                            <td class="pe-4 text-center small fw-bold">{{ date('d/m/Y', strtotime($tk->ngay_ket_thuc)) }}</td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('script')
<script>
    window.chartData = {
        labels: @json($nhan),
        data: @json($giatri)
    };
    window.exportRoute = "{{ route('dashboard.export') }}";
</script>
<script src="{{ asset('js/chart.js') }}"></script>
<script src="{{ asset('js/thongke.js') }}"></script>
@endpush
@endsection