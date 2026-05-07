<div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="offcanvasEditUser"style="width:50%;">
        <div class="offcanvas-header bg-info text-white py-4">
            <h5 class="offcanvas-title fw-bold text-white"><i class="bi bi-pencil-square me-2"></i>Chỉnh sửa thông tin</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form id="eCommerceUser-listListForm">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Họ tên</label>
                    <input type="text" class="form-control border-2 edit-name" name="name" value="{{ old('name') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Email</label>
                    <input type="text" class="form-control border-2 edit-email" name="email" value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Chức vụ & Đơn vị</label>
                    <div class="input-group">
                        <select name="chuc_vu_id" id="chuc_vu" class="form-select border-2 edit-chucvu">
                            <option value="">Chọn chức vụ</option>
                            @foreach($dschucvu as $cv)
                                <option value="{{$cv->id}}" class="menu_chucvu">
                                    {{$cv->ten_chuc_vu}}
                                </option>
                            @endforeach
                        </select>
                        <select name="don_vi_id" class="form-select border-2 edit-donvi">
                             <option class="" value="0">Chọn đơn vị</option>
                            @foreach($dsdonvi as $dsdv)
                            <option value="{{$dsdv->id}}" {{ old('don_vi_id') == $dsdv->id ? 'selected' : '' }}>{{$dsdv->ten_don_vi}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <label class="form-label fw-bold small">Quyền hệ thống</label>
                        <select class="form-select border-2 edit-role" name="role">
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-info text-white btn-lg fw-bold edit-submit shadow-sm" >Cập nhật thông tin</button>
                </div>
            </form>
        </div>
    </div>