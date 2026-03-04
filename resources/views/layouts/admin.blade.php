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
            background-color: #d5d8d8ff;
        }
        @media (min-width: 992px) { /* Chỉ kích hoạt hover trên máy tính (màn hình lớn) */
            .dropdown:hover > .dropdown-menu { 
                /* > là con trực tiếp */
                display: block;
            }
            
            /* Hiệu ứng trượt nhẹ cho đẹp */
            .dropdown-menu {
                animation: fadeIn 0.3s;
            }
        }
        /* bắt đầu từ mờ và nằm thấp hơn vị trí chuẩn 10px (translateY(10px)).
        to: Kết thúc ở trạng thái hiện rõ (opacity: 1) và trở về đúng vị trí chuẩn (translateY(0)). */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="layout-page">
        <div class="content-wrapper">
            @include('layouts.navigation')
            @include('layouts.navigation_admin')
            <!-- Main -->
            <div id="mainContent" class="col-12 px-4 pt-0">
                @yield('content')
                        
            </div>
        </div>
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // function toggleSidebar() {
        //     const sidebar = document.getElementById('sidebar');
        //     const mainContent = document.getElementById('mainContent');
        //     const main_content = document.querySelector('.main-content');
        //     sidebar.classList.toggle('d-none'); //toggle nếu có d-none thì bỏ, không có thì thêm
        //     if (sidebar.classList.contains('d-none')) {
        //         mainContent.classList.remove('col-md-10');
        //         //main_content.classList.remove('mt-4');
        //         mainContent.classList.add('col-12');
        //     } else {
        //         mainContent.classList.remove('col-12');
        //         mainContent.classList.add('col-md-10');
        //         //main_content.classList.add('mt-4');
        //     }
        // }
    </script>
    @stack('script')
</body>
</html>