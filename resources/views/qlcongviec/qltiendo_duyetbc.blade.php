@extends('layouts.admin')
@section('title', 'Quản lý tiến độ & Duyệt báo cáo')
@section('content')
<div class="card-body fs-6 mt-3">
    <div class=" border rounded">
        
        <div class="p-3 border bg-light shadow-sm p-4 rounded-2">
       <div class="row m-2">
            <div class="col-md-3">
                <h5 class="card-header"> <i class="bi bi-list-ul me-2" style="color: #3f51b5;"></i> Danh sách công việc</h5>
                    
            </div>
            <div class="col-md-9">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                    <div class="card-body">
                        <form id="mainForm" action="{{ route('system.qlcongviec.index') }}" method="GET" class="row align-items-end g-3">
                            @csrf
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-secondary small">Năm học</label>
                                <select name="filter_nh" class="form-select border-2" onchange="this.form.submit()">
                                    @foreach($namhoc as $nh)
                                        <option value="{{$nh->id}}" {{ request('filter_nh') == $nh->id ? 'selected' : '' }}>{{$nh->ten_nam_hoc}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-secondary small">Trạng thái</label>
                                <select name="trangthai" class="form-select shadow-sm text-secondary border-secondary-subtle" onchange="this.form.submit()">
                                    <option value="tat_ca" {{ request('trangthai') == 'tat_ca' ? 'selected' : '' }}> -- Tất cả -- </option>
                                    <option value="da_hoan_thanh" {{ request('trangthai') == 'da_hoan_thanh' ? 'selected' : '' }}>Đã hoàn thành</option>
                                    <option value="dang_thuc_hien" {{ request('trangthai') == 'dang_thuc_hien' ? 'selected' : '' }}>Đang thực hiện</option>
                                    <option value="dang_no" {{ request('trangthai') == 'dang_no' ? 'selected' : '' }}>Đang nợ</option>
                                    <option value="chua_dat" {{ request('trangthai') == 'chua_dat' ? 'selected' : '' }}>Chưa đạt</option>
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
                            
                            <div class="col-md-2 text-end">
                                <button type="button" onclick="submitExport()" class="btn btn-success">
                                    <i class="bi bi-box-arrow-up-right"></i> Xuất Excel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
       </div>
            <div class="table-responsive border rounded shadow-sm" style="max-height: 80vh; overflow-y: auto;">
                @include('qlcongviec.qltiendo_phe_duyet.tableTienDo')
            </div>
        </div>
    </div>
</div>     
    

@include('qlcongviec.qltiendo_phe_duyet.form_tralaibc')
@push('script')
<script src="{{ asset('js/baocao.js') }}"></script>    
<script src="{{ asset('js/thongbaoxacnhan.js') }}"></script>   
<script src="{{ asset('js/thongke.js') }}"></script>
<script>
    window.exportRoute = "{{ route('export') }}";
    const URL_XEM_BAO_CAO = "{{ route('manager.qlcongviec.qltiendo.xembc', ':id') }}";
    function toggleRule(id) {
        let el = document.getElementById("rule-" + id);
        el.classList.toggle("d-none");
    }
</script>                 
@endpush
@endsection


                