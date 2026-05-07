@extends('layouts.admin')
@section('title', 'Thư viện KPI')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y" >
    
    <div class="d-flex justify-content-between align-items-center mb-4 px-2 mt-3">
        <h4 class="fw-bold text-dark mb-0">
            <i class="bi bi-book-half me-2 text-primary"></i>Thư viện KPI mẫu
        </h4>
        <button class="btn btn-primary shadow-sm btn-add-KPI px-4 fw-bold" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddKPI" aria-controls="offcanvasAddKPI">
            <i class="bi bi-plus-lg me-1"></i> Thêm KPI vào kho
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-auto">
                    <span class="text-muted small fw-bold text-uppercase px-2">Bộ lọc:</span>
                </div>
                <div class="col-md-3">
                    <form action="" method="GET">
                        <select name="dm_id" class="form-select border-2 rounded-pill shadow-none fw-semibold text-secondary" style="font-size: 0.85rem;" onchange="this.form.submit()">
                            <option value="">-- Tất cả danh mục --</option>
                            @foreach($dmcv as $dm)
                                <option value="{{ $dm->id }}" {{ request('dm_id') == $dm->id ? 'selected' : '' }}>
                                    {{ $dm->ten_cong_viec }} - {{ $dm->donVi->ten_don_vi ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive"style="max-height: 500px; overflow-y: auto;">
            <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                    <tr class="text-muted small fw-bold text-uppercase">
                        <th class="ps-4 py-3 text-white bg-primary" style="width: 35%;">Tên KPI mẫu</th>
                        <th class="text-center text-white bg-primary">Danh mục</th>
                        <th class="text-center text-white bg-primary">Định mức</th>
                        <th class="text-center text-white bg-primary">Chu kỳ</th>
                        <th class="pe-4 text-center text-white bg-primary">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ds_kpi_mau as $kpi)
                    <tr>
                        <td class="ps-4 kpi-row-{{ $kpi->id }}">
                            <div class="fw-semibold text-dark">{{ $kpi->ten_kpi }}</div>
                            @if($kpi->ghi_chu)
                                <div class="text-muted x-small" style="font-size: 0.75rem;">{{ $kpi->ghi_chu }}</div>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-label-secondary rounded-pill px-3 text-muted">{{ $kpi->danhMuc->ten_cong_viec }}</span>
                        </td>
                        <td class="text-center fw-bold text-primary">
                            {{ $kpi->chi_tieu }} <small class="text-muted fw-normal">{{ $kpi->don_vi }}</small>
                        </td>
                        <td class="text-center">
                            <span class="text-secondary"><i class="bi bi-calendar3 me-1"></i>{{ $kpi->chu_ky }}</span>
                        </td>
                        @if(Auth::user()->role == 'manager')
                        <td class="pe-4 text-center">
                            <a href="{{ route('manager.qlcongviec.giaochitieu', ['kpi_id' => $kpi->id]) }}" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm fw-bold border-0">
                                <i class="bi bi-send-plus-fill me-1"></i> Giao chỉ tiêu
                            </a>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="offcanvasAddKPI" style="width:50%;">
        <div class="offcanvas-header bg-primary text-white py-4">
            <h5 class="offcanvas-title fw-bold text-white"><i class="bi bi-plus-circle me-2"></i>Thêm KPI vào kho</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form id="addKPI-listListForm" action="{{route('system.qlcongviec.thuvienkpi.create')}}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted small">Tên KPI mẫu</label>
                    <textarea class="form-control border-2 add-name" rows="2" placeholder="Ví dụ: Số lượng bài báo khoa học..." name="name_KPI"></textarea>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold text-muted small">Chỉ tiêu (Số)</label>
                        <input type="text" name="chi_tieu" class="form-control border-2" placeholder="Ví dụ: 10">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold text-muted small">Đơn vị tính</label>
                        <input type="text" name="don_vi" class="form-control border-2" placeholder="Ví dụ: Bài/Giờ">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-muted small">Chu kỳ</label>
                    <input type="text" name="chu_ky" class="form-control border-2" placeholder="Ví dụ: Tháng/Học kỳ/Năm">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-muted small">Danh mục công việc</label>
                    <select name="dm_id" class="form-select border-2 add-dm">
                        <option value="0">--- Chọn danh mục ---</option>
                        @foreach($dmcv as $cv)
                            <option value="{{$cv->id}}">{{$cv->ten_cong_viec}}</option>
                        @endforeach
                    </select>
                    <a href="{{route('system.dmcongviec.index')}}">+ Thêm danh mục mới</a>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">Ghi chú hướng dẫn</label>
                    <textarea name="ghi_chu" class="form-control border-2" rows="3" placeholder="Hướng dẫn cách tính hoặc minh chứng..."></textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary btn-lg fw-bold add-submit shadow-sm" onclick="xacNhan(this)" data-message="Xác nhận thêm KPI vào kho?">Thêm vào kho</button>
                    <button type="button" class="btn btn-light btn-lg text-muted" data-bs-dismiss="offcanvas">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('script')
    <script src="{{ asset('js/thongbaoxacnhan.js') }}"></script>
@endpush
@endsection