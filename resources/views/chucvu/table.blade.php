<div id="user-data-container">
    <table class="datatables-User-list-list table-user table table-hover"> 
        <thead>
            <tr>
                <th class="text-lg-center">Tên chức vụ</th>
                <th class="text-lg-center">Thao tác</th>
            </tr>
        </thead>
        <tbody>
          @foreach ($chucvu as $cv)
            <tr class="chucvu-row-{{ $cv->id }} text-lg-center">
              <td class="col-name text-lg-center">{{ $cv->ten_chuc_vu }}</td>
              <td class="text-lg-center">
                <div class="d-flex justify-content-center align-items-center gap-2">
                  <button class="btn btn-sm btn-outline-primary btn_edit_chucvu" data-chucvu-id="{{ $cv->id }}" type="button">Sửa</button>
                  <form class="border-0" action="{{route('chucvu.destroy',$cv->id)}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger btn_delete_chucvu" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
    </table>
</div>