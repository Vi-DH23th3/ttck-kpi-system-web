@extends('layouts.admin')
@section('title', 'Chi tiết KPI')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        @can('nav')
            <a href="{{ route('system.qlcongviec.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3 shadow-sm bg-white">
                <i class="bi bi-arrow-left"></i>
            </a>
        @endcan
        @can('nhanvien')
            <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3 shadow-sm bg-white">
                <i class="bi bi-arrow-left"></i>
            </a>
        @endcan
        <div>
            <h3 class="fw-bold text-dark mb-0">{{ $cv->ten_kpi }}</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item small"><i class="bi bi-person-circle me-1"></i>{{ $cv->ten_nv }}</li>
                    <li class="breadcrumb-item small active text-primary fw-bold">Chi tiết thực hiện</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-4 col-lg-5">
        <div class="sticky-top" style="top: 90px;">
            <div class="card border-0 shadow-sm ">
                <div class="card-body p-4 text-center">
                    @php
                        $trang_thai = [
                            'da_hoan_thanh'  => ['label' => 'Hoàn thành', 'class' => 'bg-success', 'icon' => 'bi-check-circle-fill'],
                            'dang_thuc_hien' => ['label' => 'Đang làm', 'class' => 'bg-primary', 'icon' => 'bi-play-circle-fill'],
                            'dang_no'        => ['label' => 'Đang nợ', 'class' => 'bg-warning text-dark', 'icon' => 'bi-clock-history'],
                            'chua_dat'       => ['label' => 'Chưa đạt', 'class' => 'bg-danger', 'icon' => 'bi-x-circle-fill'],
                        ];
                        $st = $trang_thai[$cv->trang_thai_tinh] ?? ['label' => $cv->trang_thai_tinh, 'class' => 'bg-secondary', 'icon' => 'bi-question-circle'];
                    @endphp
                    
                    <div class="display-6 mb-2"><i class="bi {{ $st['icon'] }} {{ str_replace('bg-', 'text-', $st['class']) }}"></i></div>
                    <h5 class="fw-bold">{{ $st['label'] }}</h5>
                    <p class="text-muted small mb-3">Thời hạn: {{ \Carbon\Carbon::parse($cv->phanCong->ngay_bat_dau)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($cv->phanCong->ngay_ket_thuc)->format('d/m/Y') }}</p>
                    
                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <div class="bg-light p-3 rounded-3">
                                <small class="text-muted d-block">Tiến độ</small>
                                <span class="h4 fw-bold text-primary">{{ number_format($cv->tien_do, 1) }}%</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-3 rounded-3">
                                <small class="text-muted d-block">Hiệu suất</small>
                                <span class="h4 fw-bold text-success">{{ $cv->hieu_suat }}</span>
                            </div>
                        </div>
                    </div>
                        @if(!empty($cv->phanCong->dieu_kien_phu))
                            @php
                                $dieuKien = is_array($cv->phanCong->dieu_kien_phu)
                                    ? $cv->phanCong->dieu_kien_phu
                                    : json_decode($cv->phanCong->dieu_kien_phu, true) ?? [];
                            @endphp
                            <div class="row g-2 mt-2">
                                <div class="col-12">
                                    <div class="bg-light p-3 rounded-3">
                                        @foreach($dieuKien as $dk)
                                        <div class="mb-1">
                                            <span><i class="bi bi-dot"></i> {{ $dk['ten'] ?? 'N/A' }}</span>
                                            <small class="text-primary">{{ $dk['toan_tu'] ?? '=' }}</small>
                                            <span class="fw-bold">{{ number_format($dk['gia_tri'] ?? 0) }}</span>
                                            <span class="small text-muted">({{ ($dk['pham_vi'] === 'bao_cao' ? 'Trên mỗi báo cáo' : 'Lũy kế') }})</span>
                                        </div>   
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($cv->phanCong->so_lan_toi_thieu_thang)
                        <div class="row g-2 mt-2">
                            <div class="col-12">
                                <div class="bg-light p-3 rounded-3">
                                    <span class="small">
                                        <i class="bi bi-activity"></i>
                                        Tần suất: {{ $cv->phanCong->so_lan_toi_thieu_thang }} lần / {{ $cv->phanCong->chu_ky_thang }} tháng
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($cv->canh_bao)
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mt-3">
                @php
                    $alertColor = 'success'; 
                    if (str_contains($cv->canh_bao, 'Chưa đạt chu kỳ') || 
                        str_contains($cv->canh_bao, 'Chưa đạt đa chỉ tiêu') || 
                            str_contains($cv->canh_bao, 'Quá hạn')) 
                        $alertColor = 'danger';
                    elseif (str_contains($cv->canh_bao, 'Thiếu chu kỳ') || str_contains($cv->canh_bao, 'Sắp hết hạn') || 
                            str_contains($cv->canh_bao, 'Đạt ngưỡng bù'))  
                                $alertColor = 'warning';
                    elseif (str_contains($cv->canh_bao, 'Chưa có dữ liệu') || str_contains($cv->canh_bao, 'Chưa bắt đầu'))
                         $alertColor = 'secondary';
                    elseif(str_contains($cv->canh_bao, 'Đang đúng tiến độ') )
                        $alertColor = 'info';
                @endphp
                <div class="card-header bg-{{ $alertColor }} text-{{ $alertColor == 'warning' ? 'dark' : 'white' }} border-0 fw-bold py-3">
                    <i class="bi bi-shield-exclamation me-2"></i>Thông tin cảnh báo
                </div>
                <div class="card-body p-4 bg-{{ $alertColor }}-subtle">
                    <p class="mb-0 fw-medium text-{{ $alertColor == 'warning' ? 'dark' : $alertColor }}" style="white-space: pre-line;">{{ $cv->canh_bao }}</p>
                </div>
            </div>
            @endif
        </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark">Nhật ký thực hiện</h5>
                <span class="badge bg-white text-dark border shadow-sm px-3 py-2 rounded-pill">
                    {{ collect($baoCao)->flatten()->count() }} Báo cáo
                </span>
            </div>

            <div class="timeline-container">
                @foreach($baoCao as $thang => $list)

                <div class="timeline-month mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white px-3 py-1 rounded-pill fw-bold small shadow-sm">
                            Tháng {{ \Carbon\Carbon::createFromFormat('Y-m', $thang)->format('m/Y') }}  
                        </div>
                        <div class="flex-grow-1 border-bottom ms-3 opacity-25"></div>
                    </div>

                    <div class="timeline-items ms-2">
                        @foreach($list as $bc)
@php
    $data = $bc->gia_tri_thuc_te ?? [];
    $rules = $cv->phanCong->dieu_kien_phu ?? [];
    $coLoi = false;

    if($bc->chiTietPhanCong->phanCong->loai_kpi === 'nang_cao' && $bc->tien_do_thuc < 100){
        $coLoi = true;
    }

    foreach($rules as $rule){
        $key = $rule['key'];
        $target = $rule['gia_tri'];
        $value = $data[$key] ?? 0;

        $dat = $value >= $target;
        $phamVi = $rule['pham_vi'] ?? 'tat_ca';
    
        if(!$dat){
            if($phamVi !== 'tat_ca'){
                $coLoi = true;
            }else{
                $coLoi = true;
            }
        }
    }
@endphp
                        <div class="card border-0 shadow-sm rounded-4 mb-3 timeline-card position-relative overflow-hidden {{ $coLoi  ? 'bg-danger-subtle border border-danger-subtle'  : ' ' }}">
                            @php
                                $statusMap = [
                                    'da_duyet' => ['class' => 'bg-success', 'label' => 'Đã duyệt'],
                                    'chua_duyet' => ['class' => 'bg-warning', 'label' => 'Chờ duyệt'],
                                    'tra_lai' => ['class' => 'bg-danger', 'label' => 'Trả lại']
                                ];
                                $s = $statusMap[$bc->trangthai_duyet] ?? ['class' => 'bg-secondary', 'label' => $bc->trangthai_duyet];
                            @endphp
                            <div class="status-stripe {{ $s['class'] }}"></div>
                            
                            <div class="card-body p-3 ps-4">
                                <div class="row align-items-center">
                                    <div class="col-md-6 mb-2 mb-md-0">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-3 p-2 text-center me-3" style="min-width: 60px;">
                                                <span class="d-block small text-muted text-uppercase" style="font-size: 10px;">Ngày</span>
                                                <span class="fw-bold h5 mb-0 text-dark">{{ \Carbon\Carbon::parse($bc->ngay_thuc_hien)->format('d') }}</span>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold text-dark mb-1">Báo cáo sản lượng</h6>
                                                <div class="d-flex justify-content-between align-items-center gap-2">
                                                    @if(empty($rules))   
                                                        <span class="badge bg-light text-primary border border-primary-subtle rounded-pill">
                                                        Kết quả thực hiện: 
                                                            <span class=" text-info">
                                                                {{ $bc->tien_do_thuc }} 
                                                                @if($bc->chiTietPhanCong->phanCong->loai_kpi === 'nang_cao') <span class=" text-info">%</span>@endif
                                                            </span>
                                                        </span>
                                                    @else       
                                                        @foreach($rules as $rule)
                                                            @php
                                                                $key = $rule['key'];
                                                                $target = $rule['gia_tri'];
                                                                $value = $data[$key] ?? 0;

                                                                $dat = $value >= $target;
                                                                $phamVi = $rule['pham_vi'] ?? 'tat_ca';
                                                           
                                                                if(!$dat){
                                                                    if($phamVi !== 'tat_ca'){
                                                                        $bi = 'bi-x-circle-fill ';
                                                                        $textClass = 'danger';
                                                                    }else{
                                                                        $bi = 'bi-exclamation-triangle-fill';
                                                                        $textClass = 'warning';
                                                                    }
                                                                }else {   
                                                                    $bi = 'bi-check-circle-fill';
                                                                    $textClass = 'success';
                                                                }
                                                             @endphp
                                                            
                                                            
                                                            <span class="badge bg-white text-{{ $textClass }} border border-{{ $textClass }} fw-normal">
                                                                {{ $rule['ten'] }}: <strong>{{ $value }}</strong>/{{ $target }} 
                                                                <i class="bi {{ $bi }} ms-1"></i>
                                                            </span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-md-end text-start">
                                        <span class="badge {{ $s['class'] }} rounded-pill px-3 py-2 mb-2 mb-md-0">
                                            {{ $s['label'] }}
                                        </span>
                                    </div>
                                </div>
                                @if(!empty($bc->file_minh_chung))
                                    <div class="mt-3 p-3 bg-light">
                                        <label class="small text-muted d-block mb-1"><i class="bi bi-paperclip"></i>  Minh chứng:</label>
                                        <div class="btn-group">
                                            <a href="{{ asset('storage/' . $bc->file_minh_chung) }}" target="_blank" class="btn btn-sm btn-outline-info">Xem file</a>
                                            <a href="{{ asset('storage/' . $bc->file_minh_chung) }}" download class="btn btn-sm btn-outline-primary">Tải về</a>
                                        </div>
                                    </div>
                                @endif
                                @if($bc->ghi_chu)
                                <div class="mt-3 p-3 bg-light rounded-4 border-0 small text-secondary">
                                    <i class="bi bi-quote me-1 opacity-50"></i>{{ $bc->ghi_chu }}
                                </div>
                                @endif
                                @if($bc->ly_do_tra_lai)
                                <div class="alert alert-danger mt-3 p-3 ">
                                    <i class="bi bi-quote me-1 opacity-50"></i>{{ $bc->ly_do_tra_lai }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .timeline-card:hover { transform: translateX(5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.1)!important; }

</style>
@endsection