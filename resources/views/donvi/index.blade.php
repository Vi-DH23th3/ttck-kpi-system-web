@extends('layouts.admin')
@section('title', 'Đơn vị')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="py-3 mb-0">
      <i class="bi bi-diagram-3 fs-4 text-primary"></i>
        Danh sách đơn vị
    </h4>
    @can('admin')
    <button class="btn btn-primary shadow-sm btn-add-DonVi" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddDonVi" aria-controls="offcanvasAddDonVi">
      <i class="bi bi-plus me-2"></i>Thêm đơn vị
    </button>
    @endcan
</div>
<div class="app-ecommerce-DonVi-list">
  <!-- Bảng đơn vị -->
    <div class="card bg-transparent border-0">
        @include('donvi.table')
    </div>
    <!-- Offcanvas để thêm đơn vị mới -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddDonVi" aria-labelledby="addDonViLabel">
      <!-- Header Thêm đơn vị -->
      <div class="offcanvas-header py-4">
        <h5 id="addDonViLabel" class="offcanvas-title text-muted">Thêm đơn vị mới</h5>
        <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <!-- Offcanvas Body -->
      <div class="offcanvas-body border-top">
        <form class="pt-0" id="addDonVi-listListForm" onsubmit="return true" action="{{route('admin.donvi.store')}}" method="POST">
          @csrf
          <!-- tên công việc -->
          <div class="mb-3">
            <label class="form-label text-muted" for="add-DonVi-list-title">Tên đơn vị</label>
            <input type="text" class="form-control add-name" id="add-DonVi-list-title" placeholder="Nhập đơn vị" name="ten_don_vi" aria-label="DonVi-list title">
          </div>
          <!-- Submit and reset -->
          <div class="mb-3">
            <button type="button" class="btn btn-primary me-sm-3 me-1 add-submit" onclick="xacNhan(this)" data-message="Xác nhận thêm đơn vị?">Thêm</button>
            <button type="reset" class="btn bg-danger text-white" data-bs-dismiss="offcanvas">Hủy</button>
          </div>
        </form>
      </div>
    </div>
    <!-- Form sửa -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditDonvi" aria-labelledby="offcanvasEcommerceDonvi-listListLabel">
      <!-- Offcanvas Header -->
      <div class="offcanvas-header py-4">
        <h5 id="offcanvasEcommerceDonvi-listListLabel" class="offcanvas-title text-muted">Sửa đơn vị</h5>
        <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <!-- Offcanvas Body -->
      <div class="offcanvas-body border-top">
        <form class="pt-0" id="eCommerceDonvi-listListForm" action="">
          @csrf
          <!-- Họ tên -->
          <div class="mb-3">
            <label class="form-label text-muted" for="ecommerce-Donvi-list-title">Tên đơn vị</label>
            <input type="text" class="form-control edit-name" id="edit-name" placeholder="Nhập họ tên" name="name_donvi" aria-label="Donvi-list title">
          </div>
          <!-- Submit and reset -->
          <div class="mb-3">
            <button type="button" class="btn btn-primary me-sm-3 me-1 data-submit edit-submit">Cập nhật</button>
            <button type="reset" class="btn bg-danger text-white" data-bs-dismiss="offcanvas">Hủy</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
@push('script')
   <script src="{{ asset('js/thongbaoxacnhan.js') }}"></script>
  <script src="{{ asset('js/donvi.js') }}"></script>
@endpush

@endsection