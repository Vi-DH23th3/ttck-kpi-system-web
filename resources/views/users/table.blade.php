<div id="user-data-container">
    <table class="datatables-User-list-list table-user table "> 
        <thead>
            <tr>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Chức vụ</th>
                <th class="text-nowrap text-sm-end">Phòng ban &nbsp;</th>
                <th class="text-nowrap text-sm-end">Quyền</th>
                <th class="text-lg-center">Trạng thái</th>
                <th class="text-lg-center">Actions</th>
            </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
            <tr class="user-row-{{ $user->id }}">
              <td class="col-name">{{ $user->name }}</td>
              <td class="col-email">{{ $user->email }}</td>
              <td class="col-chucvu">@if($user->chucvu == 'GD')
                    Giám đốc
                  @elseif($user->chucvu == 'TP')
                    Trưởng phòng
                  @elseif($user->chucvu == 'PTP')
                    Phó trưởng phòng
                  @elseif($user->chucvu == 'NV')
                    Nhân viên
                  @else
                    N/A
                  @endif
              </td>
              <td class="text-nowrap text-sm-end col-donvi">{{ $user->donVi ? $user->donVi->ten_don_vi : 'N/A' }}</td>
              <td class="text-nowrap text-sm-end col-role">{{ $user->role }}</td>
              <td class="text-lg-center col-trangthai">
                @if ($user->trang_thai == 0)
                    <span class="badge bg-danger">Đã khóa</span>
                @else
                    <span class="badge bg-success">Hoạt động</span>
                @endif
              </td>
              <td class="text-lg-center">
                <button class="btn btn-sm btn-outline-primary btn_edit_user" data-user-id="{{ $user->id }}" type="button">Sửa</button>
                <button type="button" class="btn btn-sm btn-danger btn_delete_user" data-user-id="{{ $user->id }}">Xóa</button>
              </td>
            </tr>
          @endforeach
        </tbody>
    </table>
</div>