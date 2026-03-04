    <div class="container-fluid bg-white shadow-sm py-1">
        <div class="container">
            <ul class="nav nav-pills nav-fill w-100 align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted py-2" href="#" id="dropDonVi" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-building me-1"></i> Đơn vị
                    </a>
                    <!-- <ul class="dropdown-menu border-0 shadow" aria-labelledby="dropDonVi">
                        <li><a class="dropdown-item" href="#">Đơn vị 1</a></li>
                        <li><a class="dropdown-item" href="#">Đơn vị 2</a></li>
                        
                    </ul> -->
                </li>

                <li class="nav-item">
                    <a class="nav-link text-muted py-2" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link text-muted py-2" href="{{ route('users.index') }}">
                        <i class="bi bi-people me-1"></i> Users
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-muted py-2" href="#">
                        <i class="bi bi-journal-text me-1"></i> Thư viện KPI
                    </a>
                </li>

                <li class="nav-item dropdown">
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