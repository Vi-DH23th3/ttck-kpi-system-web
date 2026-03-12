<div id="user-data-container">
    <table class="datatables-User-list-list table-user table table-hover"> 
        <thead>
            <tr>
                <th class="text-lg-center">Tên phòng ban</th>
                <th class="text-lg-center">Thao tác</th>
            </tr>
        </thead>
        <tbody>
          @foreach ($donvis as $donvi)
            <tr class="donvi-row-{{ $donvi->id }} text-lg-center">
              <td class="col-name text-lg-center">{{ $donvi->ten_don_vi }}</td>
              <td class="text-lg-center">
                <div class="d-flex justify-content-center align-items-center gap-2">
                  <button class="btn btn-sm btn-outline-primary btn_edit_donvi" data-donvi-id="{{ $donvi->id }}" type="button">Sửa</button>
                  <form class="border-0" action="{{route('donvi.destroy',$donvi->id)}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger btn_delete_donvi" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
    </table>
</div>