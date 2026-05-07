<div id="user-data-container" class="row g-4">
    @foreach ($donvis as $donvi)
    <div class="col-md-6 col-xl-4 donvi-row-{{ $donvi->id }}">
        <div class="card h-100 border-0 shadow-sm hover-elevate">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box bg-primary text-white rounded-3 p-3">
                        <i class="bi bi-diagram-3 fs-4"></i>
                    </div>
                    @can('admin')
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item btn_edit_donvi" href="javascript:void(0)" data-donvi-id="{{ $donvi->id }}">Sửa</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{route('admin.donvi.destroy',$donvi->id)}}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="dropdown-item text-danger"  onclick="xacNhan(this)" data-message="Xác nhận xóa đơn vị này?" type="button">Xóa</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endcan
                </div>
                <h5 class="card-title fw-bold mb-1">{{ $donvi->ten_don_vi }}</h5>
                <p class="text-muted small">Cơ cấu tổ chức hệ thống KPI</p>
            </div>
            <div class="card-footer bg-transparent border-top-0 p-4 pt-0">
                <a href="{{ route('admin.donvi.show', $donvi->id) }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill transition-all">
                    <i class="bi bi-eye me-1"></i> Xem chi tiết
                </a>  
            </div>
        </div>
    </div>
    @endforeach
</div>