@extends('layouts.admin')
@section('title', 'Chức vụ')

@section('content')
          <div class="container-xxl flex-grow-1 container-p-y">
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted">Danh sách chức vụ</span> 
</h4>

<div class="app-ecommerce-ChucVu-list">
  <!-- Bảng phòng ban -->
  <div class="card">
    <div class="card-datatable table-responsive rounded-3 shadow">
      @include('chucvu.table')
      <div class="d-flex justify-content-end align-items-center m-3">
          <button class="btn btn-primary mb-3 btn-add-ChucVu" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddChucVu" aria-controls="offcanvasAddChucVu">Thêm chức vụ</button>
        </div> 
    </div>
  </div>
  <!-- Offcanvas để thêm người dùng mới -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddChucVu" aria-labelledby="addChucVuLabel">
    <!-- Header Thêm người dùng -->
    <div class="offcanvas-header py-4">
      <h5 id="addChucVuLabel" class="offcanvas-title text-muted">Thêm chức vụ mới</h5>
      <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <!-- Offcanvas Body -->
    <div class="offcanvas-body border-top">
      <form class="pt-0" id="addChucVu-listListForm" onsubmit="return true" action="{{route('chucvu.store')}}" method="POST">
        @csrf
        <!-- Họ tên -->
        <div class="mb-3">
          <label class="form-label text-muted" for="add-ChucVu-list-title">Tên chức vụ</label>
          <input type="text" class="form-control add-name" id="add-ChucVu-list-title" placeholder="Nhập chức vụ" name="name_chucvu" aria-label="ChucVu-list title">
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
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditChucVu" aria-labelledby="offcanvasEcommerceChucVu-listListLabel">
    <!-- Offcanvas Header -->
    <div class="offcanvas-header py-4">
      <h5 id="offcanvasEcommerceChucVu-listListLabel" class="offcanvas-title text-muted">Sửa phòng ban</h5>
      <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <!-- Offcanvas Body -->
    <div class="offcanvas-body border-top">
      <form class="pt-0" id="eCommerceChucVu-listListForm" onsubmit="return true">
        @csrf
        <!-- Họ tên -->
        <div class="mb-3">
          <label class="form-label text-muted" for="ecommerce-ChucVu-list-title">Tên phòng ban</label>
          <input type="text" class="form-control edit-name" id="edit-name" placeholder="Nhập họ tên" name="name_chucvu" aria-label="ChucVu-list title">
        </div>
        <!-- Submit and reset -->
        <div class="mb-3">
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit edit-submit">Cập nhật</button>
          <button type="reset" class="btn bg-danger text-white" data-bs-dismiss="offcanvas">Hủy</button>
        </div>
      </form>
    </div>
  </div>
</div>

</div>
@push('script')
   
  <script src="{{ asset('js/chucvu.js') }}"></script>
@endpush

@endsection