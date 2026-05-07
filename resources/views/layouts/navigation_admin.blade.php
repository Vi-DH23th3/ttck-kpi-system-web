    <div class="container-fluid bg-primary shadow-sm py-1">
        <div class="container">
            <ul class="nav nav-pills nav-fill w-100 d-flex justify-content-between align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-white py-2 border-end rounded-0" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Tổng quan
                    </a>
                </li>
            @if(Auth::user()->role == 'admin')
                <li class="nav-item dropdown nav-admin">
                    <a class="nav-link dropdown-toggle text-white py-2 border-end rounded-0" href="#" id="dropQlUser" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-menu-button-wide me-1"></i>Quản lý danh mục hệ thống
                    </a>
                    <ul class="dropdown-menu border-0 shadow" aria-labelledby="dropQlUser" style="width: 300px;">
                        <li><a class="nav-link text-dark py-2 dropdown-item" href="{{ route('admin.donvi.index') }}" id="dropDonVi">
                            <i class="bi bi-building me-1"></i> Đơn vị
                            </a>
                        </li>
                        <li><a class="nav-link text-dark py-2 dropdown-item" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people me-1"></i> Nhân viên
                            </a>
                        </li>
                        <li><a class="nav-link text-dark py-2 dropdown-item" href="{{ route('admin.chucvu.index') }}">
                                <i class="bi bi-award me-1"></i> Chức vụ
                            </a>
                        </li>
                        <li><a class="nav-link text-dark py-2 dropdown-item" href="{{ route('admin.namhoc.index') }}">
                                <i class="bi bi-calendar me-1"></i> Năm học
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
                <li class="nav-item dropdown nav-admin">
                    <a class="nav-link dropdown-toggle text-white py-2 border-end rounded-0" href="#" id="dropQlCV" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-briefcase-fill me-1"></i> Thiết lập KPI
                    </a>
                    <ul class="dropdown-menu border-0 shadow" aria-labelledby="dropQlCV" style="width: 300px;">
                        
                        <li><a class="dropdown-item" href="{{route('system.dmcongviec.index')}}"><i class="bi bi-tags me-1"></i> Danh mục nhóm</a></li>
                        <li><a class="dropdown-item" href="{{route('system.qlcongviec.thuvienkpi')}}"><i class="bi bi-book me-1"></i> Thư viện KPI mẫu</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown nav-admin">
                    <a class="nav-link dropdown-toggle text-white py-2 border-end rounded-0" href="#" id="dropOps" data-bs-toggle="dropdown">
                         <i class="bi bi-gear-wide-connected me-1"></i> Vận hành
                    </a>
                    <ul class="dropdown-menu border-0 shadow" style="width: 300px;">
                        <li><a class="dropdown-item" href="{{route('system.qlcongviec.index')}}"><i class="bi bi-check-all me-1"></i> Quản lý tiến độ & Phê duyệt</a></li>
                        @can('manager')
                            <li><a class="dropdown-item" href="{{route('manager.qlcongviec.giaochitieu')}}"><i class="bi bi-plus-square me-1"></i> Giao chỉ tiêu</a></li>
                            <li><a class="dropdown-item" href="{{route('manager.phancong')}}"><i class="bi bi-list-task me-1"></i> Quản lý phân công</a></li>
                        @endcan
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white py-2" href="{{route('profile.index',Auth::user()->id)}}">
                        <i class="bi bi-clipboard-data me-1"></i>    
                        @if(Auth::user()->role == 'staff')
                            Việc của tôi (Cá nhân)
                        @elseif(Auth::user()->role == 'manager')
                            Việc tôi quản lý
                        @else
                            Tổng quan hệ thống
                        @endif
                    </a> 
                </li>
            </ul>
        </div>
    </div>