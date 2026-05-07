@extends('layouts.admin')
@section('title', 'Tổng quan KPI')

@section('content')
<div class="container-fluid py-4">

    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-body">
            <form id="mainForm" action="{{ route('dashboard') }}" method="GET" class="row align-items-end g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary small">Năm học</label>
                    <select name="filter_nh" class="form-select border-2" onchange="this.form.submit()">
                        @foreach($namhoc as $nh)
                            <option value="{{$nh->id}}" {{ request('filter_nh') == $nh->id ? 'selected' : '' }}>{{$nh->ten_nam_hoc}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary small">Phòng ban</label>
                    <select name="filter_pb" class="form-select border-2" onchange="this.form.submit()">
                        <option value="all">--- Tất cả phòng ban ---</option>
                        @foreach($phong as $pb)
                            <option value="{{$pb->id}}" {{ request('filter_pb') == $pb->id ? 'selected' : '' }}>{{$pb->ten_don_vi}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-secondary small">Nhân viên</label>
                    <select class="form-select border-2" name="filter_nv" onchange="this.form.submit()">
                        <option value="all">Tất cả</option>
                        @foreach($user as $u)   
                            <option value="{{$u->id}}" {{ request('filter_nv') == $u->id ? 'selected' : '' }}>{{$u->name}}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center p-3">
            <h6 class="text-muted">Tổng KPI</h6>
            <h2 class="fw-bold">{{ $tong }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center p-3">
            <h6 class="text-success">Hoàn thành</h6>
            <h2 class="fw-bold text-success">{{ $hoanthanh }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center p-3">
            <h6 class="text-warning">Đang thực hiện</h6>
            <h2 class="fw-bold text-warning">{{ $dangthuchien }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center p-3">
            <h6 class="text-danger">Chưa đạt</h6>
            <h2 class="fw-bold text-danger">{{ $chuadat }}</h2>
        </div>
    </div>
</div>
    <!-- CHART THỐNG KÊ -->
    <div class="row g-4 mb-4"> 
        <div class="col-md-6">
            <div class="p-4 border-0 shadow-sm bg-white" style="border-radius: 12px; height: 100%;">
                <h5 class="card-title fw-bold mb-4" style="color: #2c3e50;">
                    <i class="bi bi-bar-chart-line-fill me-2" style="color: #4e73df;"></i>Xu hướng báo cáo theo tháng
                </h5>
                <div style="height: 300px; width: 100%;">
                    <canvas id="kpiChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-4 border-0 shadow-sm bg-white" style="border-radius: 12px; height: 100%;">
                <h5 class="card-title fw-bold mb-4" style="color: #2c3e50;">
                    <i class="bi bi-pie-chart-fill me-2" style="color: #1cc88a;"></i>Phân bố trạng thái KPI
                </h5>
                <div style="height: 300px; width: 100%;">
                    <canvas id="tkKPIChart" 
                            data-ht="{{$hoanthanh}}" 
                            data-dth="{{$dangthuchien}}" 
                            data-cd="{{$chuadat}}" 
                            data-dn="{{$dangno}}">
                    </canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-4">
    @php
        $alerts = [
            ['label' => 'Sắp hết hạn', 'val' => $saphethan, 'color' => 'warning'],
            ['label' => 'Quá hạn', 'val' => $quahan, 'color' => 'danger'],
            ['label' => 'Đang nợ', 'val' => $dangno, 'color' => 'orange'],
        ];
    @endphp

    @foreach($alerts as $a)
    <div class="col-md-4">
        <div class="card border-start border-4 border-{{ $a['color'] }} shadow-sm p-3">
            <div class="d-flex justify-content-between">
                <span>{{ $a['label'] }}</span>
                <strong>{{ $a['val'] }}</strong>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="row g-4 mb-4">
    <div class="col-md-4">
        @include('tongquan.dscantheodoi')
    </div>
    <div class="col-md-4">
        @include('tongquan.topnv')
    </div>
       
    <div class="col-md-4">
         @include('tongquan.hoatdongganday')
    </div>
</div>

@push('script')
<script>
    window.chartData = {
        labels: @json($nhan),
        data: @json($giatri)
    };
    
</script>
<script src="{{ asset('js/chart.js') }}"></script>
<script src="{{ asset('js/thongke.js') }}"></script>
@endpush
@endsection