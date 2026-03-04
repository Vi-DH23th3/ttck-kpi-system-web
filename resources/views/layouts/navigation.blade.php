<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-0 flex-column align-items-stretch">
    <div class="container-fluid border-bottom border-secondary py-2">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="{{asset('storage/logos/CITFL2-01.png')}}" alt="Logo" class="me-2" style="width: 45px; height: auto;">
                <a class="navbar-brand fw-bold mb-0" href="{{ route('dashboard') }}">KPI SYSTEM</a>
            </div>

            <div class="d-flex align-items-center text-white">
                @auth
                    <div class="me-3 text-end d-none d-sm-block">
                        <small class="d-block text-secondary">Xin chào,</small>
                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm border-0" title="Đăng xuất">
                           <i class="bi bi-box-arrow-right fs-5"></i>
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
    

</nav>