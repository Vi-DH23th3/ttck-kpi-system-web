<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <img src="{{asset('storage/logos/CITFL2-01.png')}}" alt="Logo" class="me-2" style="width: 50px; height: 40px;">
        <a class="navbar-brand" href="{{ route('dashboard') }}">KPI System</a>

        <div class="collapse navbar-collapse justify-content-end">
            @auth
                <span class="text-white me-3">
                    {{ Auth::user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light btn-sm">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>