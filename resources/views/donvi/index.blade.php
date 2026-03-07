@extends('layouts.admin')
@section('title', 'Đơn vị')

@section('content')
          <div class="container-xxl flex-grow-1 container-p-y">
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted">Danh sách phòng ban</span> 
</h4>

<div class="app-ecommerce-DonVi-list">
  <!-- Bảng phòng ban -->
  <div class="card">
    <div class="card-datatable table-responsive rounded-3 shadow">
      @include('donvi.table')
      <div class="d-flex justify-content-end align-items-center m-3">
          <button class="btn btn-primary mb-3 btn-add-DonVi" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddDonVi" aria-controls="offcanvasAddDonVi">Thêm phòng ban</button>
        </div> 
    </div>
  </div>
  <!-- Offcanvas để thêm người dùng mới -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddDonVi" aria-labelledby="addDonViLabel">
    <!-- Header Thêm người dùng -->
    <div class="offcanvas-header py-4">
      <h5 id="addDonViLabel" class="offcanvas-title text-muted">Thêm phòng ban mới</h5>
      <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <!-- Offcanvas Body -->
    <div class="offcanvas-body border-top">
      <form class="pt-0" id="addDonVi-listListForm" onsubmit="return true" action="{{route('donvi.store')}}" method="POST">
        @csrf
        <!-- Họ tên -->
        <div class="mb-3">
          <label class="form-label text-muted" for="add-DonVi-list-title">Tên phòng ban</label>
          <input type="text" class="form-control add-name" id="add-DonVi-list-title" placeholder="Nhập phòng ban" name="name_donvi" aria-label="DonVi-list title">
        </div>
        <!-- Submit and reset -->
        <div class="mb-3">
          <button type="submit" class="btn btn-primary me-sm-3 me-1 add-submit">Thêm</button>
          <button type="reset" class="btn bg-danger text-white" data-bs-dismiss="offcanvas">Discard</button>
        </div>
      </form>
    </div>
  </div>
  <!-- Form sửa -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditDonvi" aria-labelledby="offcanvasEcommerceDonvi-listListLabel">
    <!-- Offcanvas Header -->
    <div class="offcanvas-header py-4">
      <h5 id="offcanvasEcommerceDonvi-listListLabel" class="offcanvas-title text-muted">Sửa phòng ban</h5>
      <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <!-- Offcanvas Body -->
    <div class="offcanvas-body border-top">
      <form class="pt-0" id="eCommerceDonvi-listListForm" onsubmit="return true">
        @csrf
        <!-- Họ tên -->
        <div class="mb-3">
          <label class="form-label text-muted" for="ecommerce-Donvi-list-title">Tên phòng ban</label>
          <input type="text" class="form-control edit-name" id="edit-name" placeholder="Nhập họ tên" name="name_donvi" aria-label="Donvi-list title">
        </div>
        <!-- Submit and reset -->
        <div class="mb-3">
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit edit-submit">Cập nhật</button>
          <button type="reset" class="btn bg-danger text-white" data-bs-dismiss="offcanvas">Discard</button>
        </div>
      </form>
    </div>
  </div>
</div>

</div>
@push('script')
   
  <script src="{{ asset('js/donvi.js') }}"></script>
@endpush

@endsection