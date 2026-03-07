    <div class="container-fluid bg-white shadow-sm py-1">
        <div class="container">
            <ul class="nav nav-pills nav-fill w-100 align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-muted py-2" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item dropdown nav-admin">
                    <a class="nav-link dropdown-toggle text-muted py-2" href="#" id="dropQlUser" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-people-fill me-1"></i>Quản lý người dùng
                    </a>
                    <ul class="dropdown-menu border-0 shadow" aria-labelledby="dropQlUser">
                        <li><a class="nav-link text-muted py-2 dropdown-item" href="{{ route('donvi.index') }}" id="dropDonVi">
                            <i class="bi bi-building me-1"></i> Đơn vị
                            </a>
                        </li>
                        <li><a class="nav-link text-muted py-2 dropdown-item" href="{{ route('users.index') }}">
                                <i class="bi bi-people me-1"></i> Users
                            </a>
                        </li>
                        <li><a class="nav-link text-muted py-2 dropdown-item" href="{{ route('chucvu.index') }}">
                                <i class="bi bi-award me-1"></i> Chức vụ
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- <li class="nav-item dropdown"> -->
                    <!-- <a class="nav-link dropdown-toggle text-muted py-2" href="{{route('donvi.index')}}" id="dropDonVi" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-building me-1"></i> Đơn vị
                    </a> Dropdown xung đột vs thẻ a, chỉ cho thả menu xuống--> 
                    <!-- <a class="nav-link text-muted py-2" href="{{ route('donvi.index') }}" id="dropDonVi">
                        <i class="bi bi-building me-1"></i> Đơn vị
                    </a> -->
                    <!-- <ul class="dropdown-menu border-0 shadow" aria-labelledby="dropDonVi">
                        <li><a class="dropdown-item" href="#">Đơn vị 1</a></li>
                        <li><a class="dropdown-item" href="#">Đơn vị 2</a></li>
                        
                    </ul> -->
                <!-- </li> -->

                
                
                <!-- <li class="nav-item">
                    <a class="nav-link text-muted py-2" href="{{ route('users.index') }}">
                        <i class="bi bi-people me-1"></i> Users
                    </a>
                </li> -->

                <li class="nav-item">
                    <a class="nav-link text-muted py-2" href="#">
                        <i class="bi bi-journal-text me-1"></i> Thư viện KPI
                    </a>
                </li>

                <li class="nav-item dropdown nav-admin">
                    <a class="nav-link dropdown-toggle text-muted py-2" href="#" id="dropQlCV" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-briefcase-fill me-1"></i> Quản lý công việc
                    </a>
                    <ul class="dropdown-menu border-0 shadow" aria-labelledby="dropQlCV">
                         <li><a class="dropdown-item" href="{{route('qlcongviec.index')}}"><i class="bi bi-hourglass-split me-1"></i> Tiến độ công việc</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-plus-square me-1"></i> Giao việc</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-arrow-up me-1"></i> Nộp báo cáo</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-check-all me-1"></i> Phê duyệt</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown nav-admin">
                    <a class="nav-link dropdown-toggle text-muted py-2" href="#" id="dropNamHoc" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-calendar-event me-1"></i> Năm học
                    </a>
                    <ul class="dropdown-menu border-0 shadow" aria-labelledby="dropNamHoc">
                        <li><a class="dropdown-item" href="#">2023-2024</a></li>
                        <li><a class="dropdown-item" href="#">2024-2025</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>