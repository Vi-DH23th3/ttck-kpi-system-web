@extends('layouts.admin')
@section('title', 'Danh mục công việc')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-2">
        <span class="text-muted">Danh sách công việc</span> 
        </h4> 
        <div class="d-flex justify-content-start">
            <!-- @foreach($dsdv as $dv) 
            <form action="" method="GET">
                @csrf
                <input type="hidden" value="{{$dv->id}}" name="id">
                <button type="submit" class="form-control btn btn-outline-secondary {{ request('id') == $dv->id ? 'active' : '' }}">{{$dv->ten_don_vi}}</button>
            </form>
            @endforeach    -->
            <div class="d-flex flex-wrap gap-2 mb-4">
                <a href="{{route('dmcongviec.index')}}" class="d-flex align-items-center py-2 px-3 shadow-sm border-0 rounded-pill text-muted text-decoration-none btn btn-outline-secondary">
                    <span class="fw-bold me-2">Tất cả</span>
                    <span class="badge bg-secondary rounded-pill">
                        {{ $tongviec}}
                    </span>
                </a>
                @foreach($dsdv as $dv) 
                    <form action="" method="GET">
                        <input type="hidden" value="{{$dv->id}}" name="id">
                        <button type="submit" class="btn {{ request('id') == $dv->id ? 'btn-primary' : 'btn-outline-secondary' }} d-flex align-items-center py-2 px-3 shadow-sm border-0 rounded-pill">
                            <span class="fw-bold me-2">{{$dv->ten_don_vi}}</span>
                            <span class="badge {{ request('id') == $dv->id ? 'bg-white text-primary' : 'bg-secondary' }} rounded-pill">
                                {{ $dv->danh_muc_count ?? 0 }}
                            </span>
                        </button>
                    </form>
                @endforeach 
            </div>
        </div>     
        
            <div class="app-ecommerce-DMCV-list">               
                <!-- Bảng danh mục công việc -->
                <div class="card">
                    <div class="card-datatable table-responsive rounded-3 shadow">
                    <div id="user-data-container">
                    <h5 class="py-3 breadcrumb-wrapper mb-4">
                    <span class="text-muted"></span> 
                    </h5>
                    <table class="datatables-User-list-list table-user table table-hover"> 
                        <thead>
                            <tr>
                                <th class="text-lg-center">Tên công việc</th>
                                <th class="text-center">Thư viện KPI</th>
                                <th class="text-lg-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($dmcv as $cv)
                            <tr class="cv-row-{{ $cv->id }} text-lg-center">
                            <td class="col-name text-lg-center">{{$cv->ten_cong_viec}}</td>
                            <td class="text-center">
                                <a href="{{ route('qlcongviec.thuvienkpi', ['dm_id' => $cv->id]) }}" class="btn btn-sm btn-label-info">
                                    <i class="bi bi-collection me-1"></i> 
                                    {{ $cv->thu_vien_k_p_i_count ?? 0 }} KPI mẫu
                                </a>
                            </td>
                            <td class="text-lg-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                <button class="btn btn-sm btn-outline-primary btn_edit_cv" data-cv-id="{{ $cv->id }}" type="button">Sửa</button>
                                <form class="border-0" action="{{route('dmcongviec.destroy',$cv->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn_delete_cv" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                </form>
                                
                                </div>
                            </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                
                
                <div class="d-flex justify-content-end align-items-center m-3">
                    <button class="btn btn-primary mb-3 btn-add-DMCV" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddDMCV" aria-controls="offcanvasAddDMCV">Thêm công việc</button>
                    </div> 
                </div>
            </div>
            
            <!-- Offcanvas để thêm công việc mới -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddDMCV" aria-labelledby="addDMCVLabel">
                <!-- Header Thêm công việc -->
                <div class="offcanvas-header py-4">
                <h5 id="addDMCVLabel" class="offcanvas-title text-muted">Thêm công việc mới</h5>
                <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <!-- Offcanvas Body -->
                <div class="offcanvas-body border-top">
                <form class="pt-0" id="addDMCV-listListForm" onsubmit="return true" action="" method="POST">
                    @csrf
                    <!-- Tên -->
                    <div class="mb-3">
                    <label class="form-label text-muted" for="add-DMCV-list-title">Tên công việc</label>
                    <input type="text" class="form-control add-name" id="add-DMCV-list-title" placeholder="Nhập công việc mới" name="name_DMCV" aria-label="DMCV-list title">
                    </div>
                    <!-- Danh sách đơn vị -->
                    <div class="mb-3">
                        <label class="form-label text-muted" for="add-User-list-image">Đơn vị</label>
                        <select name="don_vi_id" id="don_vi_add" class="form-select add-donvi">
                        <option class="" value="0">Chọn đơn vị</option>
                        @foreach($dsdv as $dv)
                        <option value="{{$dv->id}}" >{{$dv->ten_don_vi}}</option>
                        @endforeach
                        </select>
                    </div>
                    <!-- Submit and reset -->
                    <div class="mb-3">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 add-submit">Thêm</button>
                    <!-- <button type="reset" class="btn bg-danger text-white" data-bs-dismiss="offcanvas">Discard</button> -->
                    </div>
                </form>
                </div>
            </div>
            <!-- Form sửa -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditDMCV" aria-labelledby="offcanvasEcommerceDMCV-listListLabel">
                <!-- Offcanvas Header -->
                <div class="offcanvas-header py-4">
                <h5 id="offcanvasEcommerceDMCV-listListLabel" class="offcanvas-title text-muted">Sửa công việc</h5>
                <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <!-- Offcanvas Body -->
                <div class="offcanvas-body border-top">
                <form class="pt-0" id="formUpdateDMCV-listListForm" onsubmit="return true" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <!-- Họ tên -->
                    <div class="mb-3">
                    <label class="form-label text-muted" for="ecommerce-DMCV-list-title">Tên công việc</label>
                    <input type="text" class="form-control edit-name" id="edit-name" placeholder="Nhập họ tên" name="name_DMCV" aria-label="DMCV-list title">
                    </div>
                    <!-- Submit and reset -->
                    <div class="mb-3">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit edit-submit">Cập nhật</button>
                    <!-- <button type="reset" class="btn bg-danger text-white" data-bs-dismiss="offcanvas">Discard</button> -->
                    </div>
                </form>
                </div>
            </div>
            </div>

    </div>
@push('script')
   
  <script src="{{ asset('js/dmcongviec.js') }}"></script>
@endpush
@endsection