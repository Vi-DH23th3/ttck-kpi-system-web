<table class="table table-hover align-middle mb-0">
    <thead class="bg-light">
        <tr class="text-muted small fw-bold text-uppercase">
            <th class="ps-4 py-3">Thông tin nhân viên</th>
            <th>Chức vụ</th>
            <th>Phòng ban</th>
            <th class="text-center">Quyền</th>
            <th class="text-center">Trạng thái</th>
            <th class="pe-4 text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr class="user-row-{{ $user->id }}">
            <td class="ps-4">
                <div class="d-flex align-items-center">
                    <div class="avatar me-3 bg-label-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; background: #e7e7ff; color: #696cff; font-weight: bold;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="fw-bold text-dark col-name">{{ $user->name }}</div>
                        <small class="text-muted col-email">{{ $user->email }}</small>
                    </div>
                </div>
            </td>
            <td>
                <span class="col-chucvu">{{ $user->chucVu ? $user->chucVu->ten_chuc_vu : '---' }}</span>
            </td>
            <td>
                <span class="col-donvi text-muted">{{ $user->donVi ? $user->donVi->ten_don_vi : '---' }}</span>
            </td>
            <td class="text-center">
                <span class="text-capitalize small fw-bold col-role">{{ $user->role }}</span>
            </td>
            <td class="text-center col-trangthai">
                @if ($user->trang_thai == 0)
                    <span class="badge bg-danger rounded-pill px-2" style="font-size: 0.7rem;">Đã khóa</span>
                @else
                    <span class="badge bg-success rounded-pill px-2" style="font-size: 0.7rem;">Hoạt động</span>
                @endif
            </td>
            <td class="pe-4 text-center">
                <div class="d-flex justify-content-center gap-1">
                    @can('admin')
                    <button class="btn btn-sm btn-outline-primary border-0 btn_edit_user bg-light" data-url="{{ route('admin.users.edit', $user->id) }}" title="Sửa">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    @if($user->trang_thai == 0)
                    <form action="{{route('admin.users.unlock',$user->id)}}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm btn-outline-success border-0 btn_unlock_user bg-light"  type="button"
                                data-user-id="{{ $user->id }}" 
                                title="Bấm để Mở khóa"
                                onclick="xacNhan(this)" data-message="Xác nhận khôi phục tài khoản này?">
                            <i class="bi bi-unlock-fill"></i> 
                        </button>
                    </form>
                        
                    @else
                        <form action="{{route('admin.users.destroy', $user->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger border-0 btn_lock_user bg-light" 
                                    data-user-id="{{ $user->id }}" type="button"
                                    title="Bấm để Khóa" onclick="xacNhan(this)" data-message="Xác nhận khóa tài khoản này?">
                                <i class="bi bi-lock-fill"></i> 
                            </button>
                        </form>
                    @endif
                    <!-- <button class="btn btn-sm btn-outline-danger border-0 btn_delete_user bg-light" data-user-id="{{ $user->id }}" title="Xóa">
                        <i class="bi bi-trash"></i>
                    </button> -->
                    @if(!$user->google_id)
                    <form method="POST" action="{{ route('admin.users.resetpass', $user->id)  }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger border-0 bg-light" type="button" 
                         onclick="xacNhan(this)" data-message='Bạn có chắc chắn muốn đặt lại mật khẩu cho nhân viên này về 123?' title="Reset Pass">
                            <i class="bi bi-key me-1"></i> 
                        </button>
                    </form>
                    @endif
                    @endcan
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>