@extends('layouts.admin')
@section('content')
<style>
    /* Nâng cấp hiệu ứng card */
    .card { border-radius: 12px; transition: all 0.3s ease; }
    .bg-light-primary { background-color: #f0f2ff !important; color: #696cff !important; }
    
    /* Tùy chỉnh Accordion cho sang trọng */
    .accordion-item { border: 1px solid #eee !important; overflow: hidden; }
    .accordion-button:not(.collapsed) { 
        background-color: #f8f9ff !important; 
        color: #696cff !important; 
        box-shadow: none; 
    }
    .accordion-button::after { background-size: 1rem; }
    
    /* Bảng */
    .table thead th { font-size: 0.85rem; letter-spacing: 0.5px; border-bottom: none; }
    .table td { vertical-align: middle; }
    
    /* Badge trạng thái */
    .badge-soft-success { background-color: #e8fadf; color: #71dd37; }
    .badge-soft-info { background-color: #e1f5fe; color: #03a9f4; }
    .badge-soft-warning { background-color: #fff2e2; color: #ff9800; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.donvi.index') }}" class="btn btn-outline-primary btn-sm rounded-circle me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
        @else
            <a href="{{ route('profile.index') }}" class="btn btn-outline-primary btn-sm rounded-circle me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
        @endif
        <div>
            <h4 class="mb-0 fw-bold">{{ $donvi->ten_don_vi }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Đơn vị</li>
                    <li class="breadcrumb-item active">Quản lý KPI</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center pt-4">
                    <div class="icon-box bg-light-primary rounded-circle p-4 d-inline-flex mb-3">
                        <i class="bi bi-building fs-1"></i>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $donvi->ten_don_vi }}</h5>
                    <span class="badge bg-label-primary mb-4">Cơ cấu tổ chức</span>
                    
                    <div class="d-flex justify-content-around border-top pt-4">
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $donvi->users->count() }}</h5>
                            <small class="text-muted">Nhân sự</small>
                        </div>
                        <div class="border-start"></div>
                        <div>
                            <h5 class="mb-0 fw-bold text-success">
                                {{ $hoanThanh }} / {{ $tongKPI }}
                            </h5>
                            <small class="text-muted">Hoàn thành</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-list-task me-2"></i>TIẾN ĐỘ KPI THEO DANH MỤC</h6>
                </div>
                <div class="card-body px-0 pt-0">
                    <div class="accordion accordion-flush" id="kpiAccordion">
                        @foreach($group as $dmId => $danhMuc)
                        <div class="accordion-item border-top">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $dmId }}">
                                    <span class="fw-bold text-dark"><i class="bi bi-folder2-open me-2 text-warning"></i>{{$danhMuc['ten_cong_viec'] }}</span>
                                </button>
                            </h2>
                            <div id="collapse-{{ $dmId }}" class="accordion-collapse collapse" data-bs-parent="#kpiAccordion">
                                <div class="accordion-body bg-light-subtle">
                                    @foreach($danhMuc['ds_kpi'] as $kpiId => $kpi)
                                    <div class="bg-white p-3 rounded border mb-2 shadow-xs">
                                        <div class="row align-items-center bg-info">
                                            <div class="col-md-5">
                                                <div class="small text-muted mb-1">Tên chỉ tiêu KPI</div>
                                                <div class="fw-bold text-dark">{{ $kpi['ten_kpi'] }}</div>
                                                <span class="small text-muted">Người giao: {{$kpi['nguoi_giao']}}</span>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-muted mb-1 text-center">Tiến độ trung bình</div>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress w-100 me-2" style="height: 8px;">
                                                        <div class="progress-bar bg-success" style="width: {{ $kpi['tien_do_tb'] }}%"></div>
                                                    </div>
                                                    <small class="fw-bold">{{ $kpi['tien_do_tb'] }}%</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <span class="badge rounded-pill bg-light text-primary border border-primary px-3">
                                                    {{ $kpi['so_nhan_vien'] }} <i class="bi bi-people ms-1"></i>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="table-responsive mt-3 border-top pt-3">
                                            <table class="table table-sm table-borderless mb-0">
                                                <thead>
                                                    <tr class="text-muted small">
                                                        <th>Nhân viên</th>
                                                        <th class="text-center">Tiến độ</th>
                                                        <th class="text-center">Trạng thái</th>
                                                        <th class="text-end">Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($kpi['chi_tiet'] as $pc)
                                                    <tr>
                                                        <td><small class="fw-bold">{{ $pc->nguoiDuocGiao->name }}</small></td>
                                                        <td class="text-center"><span class="badge bg-label-primary text-dark">{{ $pc->tien_do }}%</span></td>
                                                        <td class="text-center">
                                                            @php
                                                                $map = [
                                                                    'da_hoan_thanh'  => ['label' => 'Đã hoàn thành', 'class' => 'bg-success'],
                                                                    'dang_thuc_hien' => ['label' => 'Đang thực hiện', 'class' => 'bg-info'],
                                                                    'chua_dat'       => ['label' => 'Chưa đạt',       'class' => 'bg-danger'],
                                                                    'dang_no'        => ['label' => 'Đang nợ',        'class' => 'bg-warning'],
                                                                    'chua_bat_dau'   => ['label' => 'Chưa bắt đầu',   'class' => 'bg-secondary'],
                                                                ];
                                                                $st = $map[$pc->trang_thai_tinh] ?? ['label' => $pc->trang_thai_tinh, 'class' => 'bg-secondary text-white'];
                                                            @endphp
                                                            <span class="badge {{ $st['class'] }} small px-2">{{ $st['label'] }}</span>
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="{{route('qlcongviec.xemlsbaocao', $pc->id)}}" class="btn btn-link btn-sm p-0 text-decoration-none">Xem lịch sử</a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection