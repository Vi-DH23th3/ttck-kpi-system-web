<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - KPI System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    
    <style>
        body {
            background: linear-gradient(to right, #0d6efd, #e965c4ff);
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row g-4">

            <!-- Sidebar -->
            <div class="col-md-2">
                <div id="sidebar" class="sidebar-fixed shadow p-4 vh-100 mt-4 bg-dark rounded text-white p-3 d-none">
                    <h4><img src="{{'storage/logos/CITFL2-01.png'}}" alt="" class="me-2" style="width: 50px; height: 40px;"><strong>KPI</strong> web</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link text-white" href="#">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="#">Users</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="#">KPI</a></li>
                    </ul>
                </div>
            </div>

            <!-- Main -->
            <div id="mainContent" class="col-12 px-4 pt-0">
                @yield('content')
                
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const main_content = document.querySelector('.main-content');
            sidebar.classList.toggle('d-none'); //toggle nếu có d-none thì bỏ, không có thì thêm
            if (sidebar.classList.contains('d-none')) {
                mainContent.classList.remove('col-md-10');
                //main_content.classList.remove('mt-4');
                mainContent.classList.add('col-12');
            } else {
                mainContent.classList.remove('col-12');
                mainContent.classList.add('col-md-10');
                //main_content.classList.add('mt-4');
            }
        }
    </script>
</body>
</html>