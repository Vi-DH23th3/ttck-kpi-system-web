@extends('layouts.admin')
@section('title', 'Thư viện KPI')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4><i class="bi bi-book-half me-2"></i>Thư viện KPI mẫu</h4>
            <button class="btn btn-primary mb-3 btn-add-KPI" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddKPI" aria-controls="offcanvasAddKPI">
                <i class="bi bi-plus-lg me-1"></i> Thêm KPI vào kho
            </button>
            <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKpiModal">
                <i class="bi bi-plus-lg me-1"></i> Thêm KPI vào kho
            </button> -->
        </div>

        <div class="d-flex justify-content-start align-items-center gap-2">
            <form action="" method="GET" class="d-inline-block w-auto">
                @csrf
                <select name="dm_id" 
                        class="form-select shadow-sm fw-semibold text-secondary border-secondary-subtle" 
                        style="font-size: 0.9rem; min-width: 200px;" 
                        onchange="this.form.submit()">
                    <option value="" class="fw-normal">-- Tất cả danh mục --</option>
                    @foreach($dmcv as $dm)
                        <option value="{{ $dm->id }}" {{ request('dm_id') == $dm->id ? 'selected' : '' }}>
                            {{ $dm->ten_cong_viec }}
                        </option>
                    @endforeach
                </select>
            </form>
            <form action="" method="GET" class="d-inline-block w-auto">
                @csrf
                <select name="nh_id" 
                        class="form-select shadow-sm fw-semibold text-secondary border-secondary-subtle" 
                        style="font-size: 0.9rem; min-width: 180px;" 
                        onchange="this.form.submit()">
                    <option value="" class="fw-normal">-- Tất cả năm học --</option>
                    @foreach($namhoc as $nh)
                        <option value="{{$nh->id}}" {{ request('nh_id') == $nh->id ? 'selected' : '' }}>
                            {{$nh->ten_nam_hoc}}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        
        <div class="table-responsive bg-white rounded shadow-sm">
            <table class="datatables-User-list-list table-user table "> 
                        <thead>
                            <tr>
                                <th class="text-start">Tên KPI mẫu</th>
                                <th class="text-lg-center">Danh mục</th>
                                <th class="text-lg-center">Định mức</th>
                                <th class="text-lg-center">Tần suất</th>
                                <th class="text-lg-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                    @foreach($ds_kpi_mau as $kpi)
                    <tr>
                        <td class="kpi-row-{{ $kpi->id }} text-start">{{ $kpi->ten_kpi }}</td>
                        <td><span class="badge border text-dark text-lg-center">{{ $kpi->danhMuc->ten_cong_viec }}</span></td>
                        <td>{{ $kpi->chi_tieu }} {{ $kpi->don_vi }}</td>
                        <td>{{ $kpi->chu_ky }}</td>
                        <td>
                            <a href="{{ route('qlcongviec.giaochitieu', ['kpi_id' => $kpi->id]) }}" class="btn btn-sm btn-success shadow-sm">
                                <i class="bi bi-send-plus-fill me-1"></i> Giao chỉ tiêu ngay
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Offcanvas để thêm kpi mới -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddKPI" aria-labelledby="addKPILabel">
                <!-- Header Thêm KPI -->
                <div class="offcanvas-header py-2">
                    <h5 id="addKPILabel" class="offcanvas-title text-muted">Thêm KPI mới</h5>
                    <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <!-- Offcanvas Body -->
                <div class="offcanvas-body border-top">
                <form class="pt-0" id="addKPI-listListForm" onsubmit="return true" action="{{route('qlcongviec.thuvienkpi.create')}}" method="POST">
                    @csrf
                    <!-- Tên -->
                    <div class="mb-3">
                        <label class="form-label text-muted" for="add-KPI-list-title">Tên KPI</label>
                        <input type="text" class="form-control add-name" id="add-KPI-list-title" placeholder="Nhập KPI mới" name="name_KPI" aria-label="KPI-list title">
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted">Chỉ tiêu</label>
                        <input type="text" name="chi_tieu" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted">Đơn vị</label>
                        <input type="text" name="don_vi" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted">Tần xuất</label>
                        <input type="text" name="chu_ky" class="form-control">
                    </div>
                    <!-- Danh sách đơn vị -->
                    <div class="mb-3">
                        <label class="form-label text-muted" for="add-DMCV-list-image">Danh mục công việc</label>
                        <select name="dm_id" id="dm_add" class="form-select add-dm">
                        <option class="" value="0">Chọn danh mục công việc</option>
                        @foreach($dmcv as $cv)
                        <option value="{{$cv->id}}" >{{$cv->ten_cong_viec}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted" for="add-User-list-image">Năm học</label>
                        <select name="nam_hoc_id" id="nam_hoc_add" class="form-select add-nam-hoc">
                        <option class="" value="0">Chọn năm học</option>
                        @foreach($namhoc as $nh)
                        <option value="{{$nh->id}}" >{{$nh->ten_nam_hoc}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted">Ghi chú (nếu có)</label>
                        <textarea name="ghi_chu" class="form-control" rows="3"></textarea>
                    </div>
                    <!-- Submit and reset -->
                    <div class="mb-3">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 add-submit">Thêm</button>
                    <!-- <button type="reset" class="btn bg-danger text-white" data-bs-dismiss="offcanvas">Discard</button> -->
                    </div>
                </form>
                </div>
            </div>
    </div>
@endsection