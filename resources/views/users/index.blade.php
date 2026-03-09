@extends('layouts.admin')
@section('title', 'Users')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex">
      <div class="">
        <button class="btn btn-muted mb-3 btn-add-user py-3 mb-4" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddFilter" aria-controls="offcanvasAddFilter">
          <i class="bi bi-filter"></i>
        </button>
      </div>
        <div class="d-flex justify-content-between align-items-center mb-3 w-100">
          <h4 class="text-muted mb-0">
              <i class="bi bi-book-half me-2"></i>Danh sách người dùng
          </h4>
          <button class="btn btn-primary btn-add-user" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser">
              <i class="bi bi-plus-lg me-1"></i>Thêm người dùng
          </button>
        </div>
    </div>

    <div class="app-ecommerce-User-list">
      <!-- Bảng người dùng -->
      <div class="card">
        <div class="card-datatable table-responsive rounded-3 shadow">
          <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasAddFilter" aria-labelledby="addFilterLabel">
            <!-- Header Thêm người dùng -->
            <div class="offcanvas-header py-4">
              <h5 id="addUserLabel" class="offcanvas-title text-muted">Filter user</h5>
              <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body border-top">
          
            <!-- Lọc theo đơn vị -->
            <form class="form-control w-auto m-2" method="GET" action="">   
              @csrf                  
              <select name="filter_don_vi" class="form-select border-0" event="change" onchange="this.form.submit()">
                <option value="">Lọc theo đơn vị</option>
                @foreach($dsdonvi as $dsdv)
                <option value="{{$dsdv->id}}" {{ request('filter_don_vi') == $dsdv->id ? 'selected' : '' }}>{{$dsdv->ten_don_vi}}</option>
                @endforeach
              </select>
            </form>
            <!-- Lọc theo chức vụ -->
            <form class="form-control w-auto m-2" method="GET" action="">   
              @csrf                  
              <select name="filter_chucvu" class="form-select border-0" event="change" onchange="this.form.submit()">
                <option value="">Lọc theo chức vụ</option>
                @foreach($dschucvu as $cv)
                <option value="{{$cv->id}}" {{ request('filter_chucvu') == $cv->id ? 'selected' : '' }}>{{$cv->ten_chuc_vu}}</option>
                @endforeach
              </select> 
            </form>
            <!-- Lọc theo trạng thái -->
            <form class="form-control w-auto m-2" method="GET" action="">   
              @csrf                  
              <select name="filter_trang_thai" class="form-select border-0" event="change" onchange="this.form.submit()">
                <option value="">Lọc theo trạng thái</option>
                <option value="1" {{ request('filter_trang_thai') == '1' ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ request('filter_trang_thai') == '0' ? 'selected' : '' }}>Bị khóa</option>
              </select> 
            </form>
            <!-- Lọc theo quyền -->
            <form class="form-control w-auto m-2" method="GET" action="">   
              @csrf                  
              <select name="filter_role" class="form-select border-0" event="change" onchange="this.form.submit()">
                <option value="">Lọc theo quyền</option>
                <option value="admin" {{ request('filter_role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="manager" {{ request('filter_role') == 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="staff" {{ request('filter_role') == 'staff' ? 'selected' : '' }}>Staff</option>
              </select>
            </form>
            <!-- Lọc người dùng chưa đổi mật khẩu -->
            <form action="" class="form-control w-auto m-2" method="GET">
              @csrf                  
              <select name="filter_change_password" class="form-select border-0" event="change" onchange="this.form.submit()">
                <option value="">Lọc người dùng chưa đổi mật khẩu mặc định</option>
                <option value="0" {{ request('filter_change_password') == '0' ? 'selected' : '' }}>Đã đổi</option>
                <option value="1" {{ request('filter_change_password') == '1' ? 'selected' : '' }}>Chưa đổi</option>
              </select> 

            </form>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-2">  
            <!-- Thêm người dùng bằng file -->
            <div class="d-flex justify-content-between align-items-center p-3 gap-2"> 
              <form class="form-control w-100 d-flex" method="POST" action="{{ route('users.import') }}" enctype="multipart/form-data">
                @csrf
                <input type="file" name="import_file" id="import_file" class="form-control mb-3" accept=".xlsx, .xls">
                <button type="submit" class="btn btn-success mb-3 btn-import">Import</button>
              </form>
            </div>
          </div>
          
          @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          @if($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
          @include('users.table')
          <div class="pagination-wrapper d-flex justify-content-center mt-3">
            {{ $users->links() }}
        </div>
        </div>
      </div>
      <!-- Offcanvas để thêm người dùng mới -->
      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="addUserLabel">
        <!-- Header Thêm người dùng -->
        <div class="offcanvas-header py-4">
          <h5 id="addUserLabel" class="offcanvas-title text-muted">Thêm người dùng mới</h5>
          <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <!-- Offcanvas Body -->
        <div class="offcanvas-body border-top">
          <form class="pt-0" id="addUser-listListForm" onsubmit="return true">
            <!-- Họ tên -->
            <div class="mb-3">
              <label class="form-label text-muted" for="add-User-list-title">Họ tên</label>
              <input type="text" class="form-control add-name" id="add-User-list-title" placeholder="Nhập họ tên" name="name" aria-label="User-list title">
            </div>
            <!-- Email -->
            <div class="mb-3">
              <label class="form-label text-muted" for="add-User-list-slug">Email</label>
              <input type="text" id="add-User-list-slug" class="form-control add-email" placeholder="Nhập email" aria-label="email" name="email">
            </div>
            <!-- Chức vụ -->
            <div class="mb-3">
              <label class="form-label text-muted" for="add-User-list-image">Chức vụ</label>
              <select name="chuc_vu" id="chuc_vu_add" class="form-select">
                <option class="menu_chucvu" value="">Chọn chức vụ</option>
                @foreach($dschucvu as $cv)
                <option value="{{$cv->id}}" class="menu_chucvu">{{$cv->ten_chuc_vu}}</option>
                @endforeach
                <!-- <option class="menu_chucvu" value="GD">Giám đốc</option>
                <option class="menu_chucvu" value="TP">Trưởng phòng</option>
                <option class="menu_chucvu" value="PTP">Phó trưởng phòng</option>
                <option class="menu_chucvu" value="NV">Nhân viên</option> -->
              </select>
              <a href="">Thêm chức vụ mới</a>
            </div>
            <!-- Danh sách quyền -->
            <div class="mb-3 add-select2-dropdown">
              <label class="form-label text-muted" for="add-User-list-parent-User-list">Phân quyền</label>
              <select id="add-User-list-parent-User-list" class="select2 form-select add-role" data-placeholder="Select parent User-list" name="role">
                <option class="menu_role" value="">Chọn phân quyền</option>
                <option class="menu_role" value="admin">Admin</option>
                <option class="menu_role" value="manager">Manager</option>
                <option class="menu_role" value="staff">Staff</option>
              </select>
            </div>
            <!-- Danh sách đơn vị -->
              <div class="mb-3">
                <label class="form-label text-muted" for="add-User-list-image">Đơn vị</label>
                <select name="don_vi_id" id="don_vi_add" class="form-select add-donvi">
                  <option class="" value="0">Chọn đơn vị</option>
                  @foreach($dsdonvi as $dsdv)
                  <option value="{{$dsdv->id}}" >{{$dsdv->ten_don_vi}}</option>
                  @endforeach
                  <!-- <option value="1">Ban giám đốc</option>
                  <option value="2">Phòng công nghệ thông tin</option> -->
                </select>
              </div>
            <!-- Mật khẩu -->
            <div class="mb-3">
              <label class="form-label text-muted" for="add-User-list-password">Mật khẩu</label>
              <input type="password" id="add-User-list-password" class="form-control add-password" placeholder="Nhập mật khẩu" name="password" value="12345678">
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
      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditUser" aria-labelledby="offcanvasEcommerceUser-listListLabel">
        <!-- Offcanvas Header -->
        <div class="offcanvas-header py-4">
          <h5 id="offcanvasEcommerceUser-listListLabel" class="offcanvas-title text-muted">Sửa người dùng</h5>
          <button type="button" class="btn-close bg-label-secondary text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <!-- Offcanvas Body -->
        <div class="offcanvas-body border-top">
          <form class="pt-0" id="eCommerceUser-listListForm" onsubmit="return true">
            <!-- Họ tên -->
            <div class="mb-3">
              <label class="form-label text-muted" for="ecommerce-User-list-title" >Họ tên</label>
              <input type="text" class="form-control edit-name" id="edit-name" placeholder="Nhập họ tên" name="name" aria-label="User-list title">
            </div>
            <!-- Email -->
            <div class="mb-3">
              <label class="form-label text-muted" for="ecommerce-User-list-slug">Email</label>
              <input type="text" id="edit-email" class="form-control edit-email" placeholder="Nhập email" aria-label="email" name="email">
            </div>
            <!-- Danh sách chức vụ -->
            <div class="mb-3">
              <label class="form-label text-muted" for="ecommerce-User-list-image">Chức vụ</label>
              <select name="chuc_vu" id="chuc_vu" class="form-select edit-chucvu">
                <!-- <option class="" value="">Chọn chức vụ</option> -->
                <!-- @foreach($dschucvu as $cv)
                <option value="{{$cv->id}}" class="menu_chucvu">{{$cv->ten_chuc_vu}}</option>
                @endforeach -->
                <!-- <option class="GD" value="GD">Giám đốc</option>
                <option class="TP" value="TP">Trưởng phòng</option>
                <option class="PTP" value="PTP">Phó trưởng phòng</option>
                <option class="NV" value="NV">Nhân viên</option> -->
              </select>
            </div>
            <!-- Danh sách đơn vị -->
            <div class="mb-3">
              <label class="form-label text-muted" for="ecommerce-User-list-image">Đơn vị</label>
              <select name="don_vi_id" id="don_vi_edit" class="form-select edit-donvi">
              
              </select>
            </div>
            <!-- Danh sách quyền -->
            <div class="mb-3 ecommerce-select2-dropdown">
              <label class="form-label text-muted" for="ecommerce-User-list-parent-User-list">Phân quyền</label>
              <select id="role" class="select2 form-select edit-role" name="role">
                <option class="" value="">Chọn phân quyền</option>
                <option class="admin" value="admin">Admin</option>
                <option class="manager" value="manager">Manager</option>
                <option class="staff" value="staff">Staff</option>
              </select>
            </div>
            <!-- Trạng thái tài khoản -->
            <div class="mb-3 ecommerce-select2-dropdown">
              <label class="form-label text-muted" for="ecommerce-User-list-parent-User-list">Trạng thái tài khoản</label>
              <select id="trangthai" class="select2 form-select edit-trangthai" data-placeholder="Select parent User-list" name="status">
                <option class="" value="">Chọn trạng thái</option>
                <option class="active" value="1">Kích hoạt(Hoạt động)</option>
                <option class="inactive" value="0">Khóa</option>
              </select>
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

  <script src="{{ asset('js/user.js') }}"></script>
@endpush

@endsection