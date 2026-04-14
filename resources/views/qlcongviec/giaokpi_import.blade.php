@extends('layouts.admin')
@section('title', 'Giao việc')

@section('content')
 <div class="container-fluid row justify-content-center">
    <div class="col-lg-12 card shadow-sm">
        <div class="card-header bg-secondary text-white py-3">
            <h5 class="card-title mb-0"><i class="bi bi-person-plus-fill me-2"></i>Giao Chỉ Tiêu KPI Mới</h5>
        </div>
        <div class="d-flex justify-content-between align-items-center p-2 gap-2"> 
            <div class="w-50">
                <form class="w-100 d-flex" method="POST" action="{{ route('qlcongviec.giaokpiimport') }}" enctype="multipart/form-data">
                @csrf
                <input type="file" name="import_file_kpi" id="import_file_kpi" class="form-control mb-3" accept=".xlsx, .xls">
                <button type="submit" class="btn btn-success m-2  btn-import">Import </button>
                </form> 
            </div> 
        </div> 
        <form action="{{ route('qlcongviec.giaokpiimport.store') }}" method="POST">  
            @csrf 
            <div class="d-flex justify-content-between align-items-center p-2 gap-2 w-50">         
            <select name="don_vi_id" class="form-select border w-50" >
                <option value="">Chọn phòng ban</option>
                @foreach($dsdonvi as $dsdv)
                    <option value="{{$dsdv->id}}" {{ request('don_vi_id') == $dsdv->id ? 'selected' : '' }}>{{$dsdv->ten_don_vi}}</option>
                @endforeach
            </select>
            </div> 
        
            @if(session('kpi_import'))
            <div class="card-body">
                <div class="table-responsive border rounded" style="height: auto; overflow-y: auto;" id="giaochitieu-table-user">
                    <table class="table table-sm table-hover mb-0" >
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>STT</th>
                                <th>Danh mục công việc</th>
                                <th class="text-center">Nội dung KPI</th>
                                <th>Chỉ tiêu</th>
                                <th>Đơn vị</th>
                                <th>Chu kỳ</th>
                                <th>Ghi chú</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Mức độ ưu tiên</th>
                                <th>Phân loại</th>
                                <th>Danh sách nhân viên</th>
                                <th>Chọn nhân viên nhận việc</th> 
                                
                            </tr>
                        </thead>
                        <tbody id="userTable">             
                            @foreach(session('kpi_import') as $index => $item)
                            <tr class="kpi-row" data-index="{{ $index }}">
                                <td>{{$index+1}}</td>
                                <td class="fw-bold text-primary">{{ $item['danh_muc'] }}
                                    <input type="hidden" name="tasks[{{$index}}][danh_muc]" value="{{ $item['danh_muc'] }}">
                                </td>
                                <td>{{ $item['ten_kpi'] }}
                                    <input type="hidden" name="tasks[{{$index}}][ten_kpi]" value="{{ $item['ten_kpi'] }}">
                                    
                                </td>
                                <td><input  class="form-control" type="number" name="tasks[{{$index}}][chi_tieu]" value="{{ $item['chi_tieu'] }}"></td>
                                <td><input class="form-control" type="text" name="tasks[{{$index}}][don_vi]" value="{{ $item['don_vi'] }}"></td>
                                <td><input class="form-control" type="text" name="tasks[{{$index}}][chu_ky]" value="{{ $item['chu_ky'] }}"></td>
                                <td><input class="form-control" type="text" name="tasks[{{$index}}][ghi_chu]" value="{{ $item['ghi_chu'] }}"></td>
                                <td><input class="form-control" type="date" name="tasks[{{$index}}][ngay_bat_dau]" value="{{ $namhoc ? $namhoc->ngay_bat_dau : date('Y-m-d') }}"></td>
                                <td><input class="form-control" type="date" name="tasks[{{$index}}][ngay_ket_thuc]" value="{{ $namhoc ? $namhoc->ngay_ket_thuc : date('Y-m-d') }}"></td>
                                <td>
                                    <select name="tasks[{{$index}}][muc_do_uu_tien]" class="form-select">
                                        <option value="1">Thấp</option>
                                        <option value="2" selected>Trung bình</option>
                                        <option value="3">Cao / Gấp</option>
                                    </select>
                                </td>
                               <td>
                                    <div class="form-check mb-1">
                                        <input type="radio" name="tasks[{{ $index }}][loai_kpi]" value="don_gian" checked class="loai-kpi-radio">
                                        <label class="form-check-label text-nowrap">
                                            Đơn giản <small class="text-muted">(Chỉ đếm tổng)</small>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" name="tasks[{{ $index }}][loai_kpi]" value="nang_cao" class="loai-kpi-radio">
                                        <label class="form-check-label text-primary fw-bold text-nowrap">
                                            Nâng cao <small>(Thiết lập ràng buộc)</small>
                                        </label>
                                    </div>

                                    <div class="nang_cao_config d-none p-2 border rounded bg-light mt-2">
                                        <h6 class="small border-bottom pb-1 mb-2 text-primary fw-bold">Cấu hình nâng cao</h6>
                                        
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="small text-muted">Số lần tối thiểu</label>
                                                <input type="number" name="tasks[{{ $index }}][so_lan_toi_thieu_thang]" class="form-control form-control-sm" placeholder="Ví dụ: 1">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small text-muted">Chu kỳ tháng(bao nhiêu tháng/lần)</label>
                                                <input type="number" name="tasks[{{ $index }}][chu_ky_thang]" class="form-control form-control-sm" placeholder="Ví dụ: 1">
                                            </div>
                                            <div class="col-12">
                                                <label class="small text-muted">Thiết lập điều kiện phụ (Nếu có)</label>
                                                <div class="dynamic-rules-container"></div>
                                                <button type="button" class="btn btn-outline-secondary btn-sm mt-2 add-rule-btn">
                                                    <i class="bi bi-plus-circle"></i> + Thêm điều kiện mới
                                                </button> 
                                            </div>                                                                                     
                                        </div>

                                        <div class="mt-2">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" name="tasks[{{ $index }}][cho_phep_bu]" value="1" class="form-check-input cho_phep_bu" id="cho_phep_bu_{{ $index }}">
                                                <label class="small fw-bold" for="cho_phep_bu_{{ $index }}">Cho phép bù nợ (nếu có)</label>
                                            </div>
                                        </div>

                                        <div class="nguong_bu_container d-none mt-2">
                                            <label class="small text-muted">Ngưỡng tối thiểu (%)</label>
                                            <input type="number" name="tasks[{{ $index }}][nguong_duoc_bu]" class="form-control form-control-sm" placeholder="50">
                                        </div>
                                    </div>
                                   
                                </td>
                                <td data-kpi-users="{{$index}}"></td>
                                <td>
                                    <div class="selected-users-list d-flex flex-wrap gap-1 mb-2">
                                        <button class="btn btn-primary btn-add-user-kpi" data-index="{{$index}}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUserKPI" aria-controls="offcanvasAddUserKPI" type="button">
                                            Chọn nhân viên
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle me-2"></i>Xác nhận giao việc</button>
                </div>
            </div>
            @endif
        </form> 
    </div>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUserKPI" aria-labelledby="addUserKPILabel">
                <!-- Header Thêm KPI -->
                <div class="offcanvas-header py-2">
                    <h5 id="addKPILabel" class="offcanvas-title text-muted">Chọn nhân viên cho KPI dòng #<span id="display-index"></span></h5>
                    <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <!-- Offcanvas Body -->
                <div class="offcanvas-body border-top">
                    <input type="hidden" id="kpi-index">
                    <form class="pt-0" id="addKPI-listListForm" onsubmit="return true" action="" method="POST">
                        @csrf
                        <table class="table table-sm table-hover mb-0" >
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th width="40" class="text-center">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th>Họ tên</th>
                                </tr>
                            </thead>
                            <tbody id="userTable">
                                @foreach($users as $user)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="user-checkbox" value="{{$user->id}}" class="form-check-input user-checkbox" data-name="{{ $user->name }}">
                                    </td>
                                    <td>{{$user->name}} <small class="text-muted">({{$user->donVi->ten_don_vi ?? 'N/A'}})</small></td>
                                    <!-- <p>{{$user->name}}</p> -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1" id="submit-user-kpi">Chọn</button>
                    </div>
                </div>
            </div>    
</div>
<template id="rule-row-template">
    <div class="row g-2 mb-2 rule-row">
        <div class="col-6"><input type="text" data-field="dieu_kien_phu_ten" class="form-control"></div>
        <div class="col-4"><input type="number" data-field="dieu_kien_phu_gia_tri" class="form-control"></div>
        <div class="col-2"><button type="button" class="btn btn-danger btn-remove-rule">X</button></div>
    </div>
</template>
@push('script')
  <script src="{{ asset('js/giaokpifile.js') }}"></script>
@endpush
@endsection