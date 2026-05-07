<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    
    <style>
        body {
            background-color: #dae7f1ff;
        }
        @media (min-width: 992px) { 
            .nav-admin:hover > .dropdown-menu { 
                display: block;
            }
            
            .dropdown-menu {
                animation:  ease fadeIn 0.5s;
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(10px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
    
</head>
<body>
<div class="layout-page">
        <div class="content-wrapper">
            @include('layouts.navigation')
            @can('nav')
            @include('layouts.navigation_admin')
            @endcan
            <!-- Main -->
            <div id="mainContent" class="col-12 px-4 pt-0 w-100">
                @yield('content')
                        
            </div>
        </div>
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @include('layouts.message')
    @stack('script')
</body>
</html>