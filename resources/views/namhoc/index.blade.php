@extends('layouts.admin')
@section('title', 'Năm học')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="py-3 mb-0">
            Danh sách năm học
        </h4>
        @can('admin')
        <button class="btn btn-primary shadow-sm btn-add-NamHoc" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddNamHoc">
            <i class="bi bi-plus me-2"></i> Thêm năm học mới
        </button>
        @endcan
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-datatable table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 100px;">STT</th>
                        <th>Tên năm học</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th class="text-center" style="width: 200px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($namhoc as $index => $nh)
                    <tr class="namhoc-row-{{ $nh->id }}">
                        <td class="ps-4 text-muted fw-bold">{{ $index + 1 }}</td>
                        <td>
                            <span class="fw-bold text-dark">{{ $nh->ten_nam_hoc }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($nh->ngay_bat_dau)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($nh->ngay_ket_thuc)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            @can('admin')
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-icon btn-outline-primary btn-sm btn_edit_namhoc" 
                                        data-namhoc-id="{{ $nh->id }}" 
                                        data-ten-nh="{{ $nh->ten_nam_hoc }}"
                                        data-ngay-bat-dau="{{ \Carbon\Carbon::parse($nh->ngay_bat_dau)->format('Y-m-d') }}"
                                        data-ngay-ket-thuc="{{ \Carbon\Carbon::parse($nh->ngay_ket_thuc)->format('Y-m-d') }}"
                                        title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                
                                <form action="{{route('namhoc.destroy',$nh->id)}}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-outline-danger btn-sm" 
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa năm học này?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
 <!-- Offcanvas để thêm năm học mới -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddNamHoc" aria-labelledby="addNamHocLabel">
        <!-- Header Thêm năm học -->
        <div class="offcanvas-header py-4">
            <h5 id="addNamHocLabel" class="offcanvas-title text-muted">Thêm năm học mới</h5>
            <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <!-- Offcanvas Body -->
        <div class="offcanvas-body border-top">
            <form class="pt-0" id="addNamHoc-listListForm" onsubmit="return true" action="{{route('namhoc.store')}}" method="POST">
            @csrf
            <!-- Tên năm học -->
            <div class="mb-3">
                <label class="form-label text-muted" for="add-NamHoc-list-title">Tên năm học</label>
                <input type="text" class="form-control add-name" id="add-NamHoc-list-title" placeholder="Nhập năm học" name="ten_nam_hoc" aria-label="NamHoc-list title">
            </div>
            <!-- Ngày bắt đầu -->
                <div class="mb-3">
                    <label class="form-label text-muted" for="ecommerce-NamHoc-list-start-date">Ngày bắt đầu</label>
                    <input type="date" class="form-control add-start-date" id="add-start-date" name="ngay_bat_dau" aria-label="NamHoc-list start date">
                </div>
                    <!-- Ngày kết thúc -->
                <div class="mb-3">
                    <label class="form-label text-muted" for="ecommerce-NamHoc-list-end-date">Ngày kết thúc</label>
                    <input type="date" class="form-control add-end-date" id="add-end-date" name="ngay_ket_thuc" aria-label="NamHoc-list end date">
                </div>
            <!-- Submit and reset -->
            <div class="mb-3">
                <button type="submit" class="btn btn-primary me-sm-3 me-1 add-submit">Thêm</button>
                <button type="reset" class="btn bg-danger text-white" data-bs-dismiss="offcanvas">Hủy</button>
            </div>
            </form>
        </div>
    </div>
    <!-- Form sửa -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditNamHoc" aria-labelledby="offcanvasEcommerceNamHoc-listListLabel">
      <!-- Offcanvas Header -->
        <div class="offcanvas-header py-4">
            <h5 id="offcanvasEcommerceNamHoc-listListLabel" class="offcanvas-title text-muted">Sửa năm học</h5>
            <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
      <!-- Offcanvas Body -->
        <div class="offcanvas-body border-top">
            <p class="idnh"></p>
            <form class="pt-0" id="eCommerceNamHoc-listListForm" onsubmit="return true" method="POST" action="">
            @csrf
            <input type="hidden" class="edit-id" name="id_namhoc">
            <!-- Tên năm học -->
            <div class="mb-3">
                <label class="form-label text-muted" for="ecommerce-NamHoc-list-title">Tên năm học</label>
                <input type="text" class="form-control edit-name" id="edit-name" placeholder="Nhập năm học" name="ten_nam_hoc" aria-label="NamHoc-list title">
            </div>
            <!-- Ngày bắt đầu -->
            <div class="mb-3">
                <label class="form-label text-muted" for="ecommerce-NamHoc-list-start-date">Ngày bắt đầu</label>
                <input type="date" class="form-control edit-start-date" id="edit-start-date" name="ngay_bat_dau" aria-label="NamHoc-list start date">
            </div>
                <!-- Ngày kết thúc -->
            <div class="mb-3">
                <label class="form-label text-muted" for="ecommerce-NamHoc-list-end-date">Ngày kết thúc</label>
                <input type="date" class="form-control edit-end-date" id="edit-end-date" name="ngay_ket_thuc" aria-label="NamHoc-list end date">
            </div>
            <!-- Submit and reset -->
            <div class="mb-3">
                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit edit-submit" onclick="return confirm('Bạn có chắc chắn muốn cập nhật năm học này?')">
                    Cập nhật
                </button>
                <button type="reset" class="btn bg-danger text-white" data-bs-dismiss="offcanvas">Hủy</button>
            </div>
            </form>
        </div>
    </div>
  </div>

  </div>
  @push('script')
    
    <script src="{{ asset('js/namhoc.js') }}"></script>
  @endpush

  @endsection