<div class="table-responsive border rounded" style="height: 300px; overflow-y: auto;" id="giaochitieu-table-user">
    <table class="table table-sm table-hover mb-0" >
        <thead class="table-light sticky-top">
            <tr>
                <th width="40" class="text-center">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th>Họ tên</th>
            </tr>
        </thead>
        <tbody id="userTable">
            @foreach($users as $user)
            <tr>
                <td class="text-center">
                    <input type="checkbox" name="user_ids[]" value="{{$user->id}}" class="form-check-input user-checkbox">
                </td>
                <td>{{$user->name}} <small class="text-muted">({{$user->donVi->ten_don_vi ?? 'N/A'}})</small></td>
                <!-- <p>{{$user->name}}</p> -->
            </tr>
            @endforeach
        </tbody>
    </table>
</div>