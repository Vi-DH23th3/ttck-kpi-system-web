<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-0 flex-column align-items-stretch sticky-top">
    <div class="container-fluid border-bottom border-secondary py-2">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="{{asset('storage/logos/CITFL2-01.png')}}" alt="Logo" class="me-2" style="width: 45px; height: auto;">
                <a class="navbar-brand fw-bold mb-0" href="{{ route('dashboard') }}">KPI SYSTEM</a>
            </div>
            
                <div class="d-flex align-items-center text-white">
                    <a class="nav-link text-white py-2" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                    <button type="button" class="btn btn-outline-secondary text-white border-0 btn-search "><i class="bi bi-search"></i></button>
                @auth
                    <ul class="nav nav-pills nav-fill align-items-center mr-2 ">
                        <li class="nav-item dropdown ">
                            <button type="button" class="btn position-relative " data-bs-toggle="dropdown">
                                <i class="bi bi-bell-fill fs-4 text-white"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="margin-left: -5px; margin-bottom: -5px;">5</span>
                            </button>
                             <ul class="dropdown-menu dropdown-menu-end border-0 shadow " aria-labelledby="dropDonVi" style="min-width: 300px;">
                                <li class="px-3 py-2 border-bottom dropdown-item">
                                    <span class="fw-bold">Thông báo</span>
                                </li>
                                
                                <li class="dropdown-item">
                                    <a class="dropdown-item py-3 border-bottom" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle p-2 me-3">
                                                <i class="bi bi-briefcase"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 small fw-bold">Công việc mới được giao</p>
                                                <small class="text-muted">Bạn có 1 nhiệm vụ mới từ Admin</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>

                                <li class="dropdown-item">
                                    <a class="dropdown-item py-3 text-center text-primary fw-bold" href="#">
                                        Xem tất cả
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div class="me-3 text-end d-none d-sm-block p-3">
                        <small class="d-block text-secondary">Xin chào,</small>
                        <span class="fw-bold"><a href="{{route('profile.index',Auth::user()->id)}}">{{ Auth::user()->name }}</a></span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm border-0" title="Đăng xuất">
                           <i class="bi bi-box-arrow-right fs-5"></i>
                        </button>
                    </form>
                @endauth
                 
                <div class="nav-search d-none">
                    <form class="form-control w-auto" method="GET" action="">   
                        @csrf                  
                        <input type="text" class="border-0" name="search" placeholder="Search" value="{{request('search')}}">  
                        <button type="submit" class="btn btn-outline-secondary border-0"><i class="bi bi-search"></i></button> 
                        <button class="btn-close" type="button"></button>
                        <!-- <i class="bi bi-x-lg"></i> -->
                    </form> 
                </div>
                </div>
        </div>
    </div>
    

</nav>
<script>
    let search = document.querySelector('.nav-search');
    let btn = document.querySelector('.btn-search');
    let btn_close = document.querySelector('.btn-close');
    btn.addEventListener('click', function(){
        search.classList.remove('d-none');
        btn.classList.add('d-none');
    })
    btn_close.addEventListener('click', function(){
        search.classList.add('d-none');
        btn.classList.remove('d-none');
    })
</script>