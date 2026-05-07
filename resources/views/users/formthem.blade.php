<div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="offcanvasAddUser" style="width:50%;">
        <div class="offcanvas-header bg-primary text-white py-4">
            <h5 class="offcanvas-title fw-bold text-white"><i class="bi bi-person-plus me-2"></i>Thêm người dùng mới</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form id="addUser-listListForm">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Họ tên nhân viên</label>
                    <input type="text" class="form-control border-2 add-name" placeholder="Nguyễn Văn A" name="name">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Email (Tài khoản)</label>
                    <input type="email" class="form-control border-2 add-email" placeholder="email@vi du.com" name="email">
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold small">Chức vụ</label>
                        <select name="chuc_vu_id" class="form-select border-2" id="chuc_vu_add">
                            <option value="">Chọn chức vụ</option>
                            @foreach($dschucvu as $cv)
                                <option value="{{$cv->id}}">{{$cv->ten_chuc_vu}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold small">Phân quyền</label>
                        <select class="form-select border-2 add-role" name="role">
                            <option value="">Chọn quyền</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Đơn vị công tác</label>
                    <select name="don_vi_id" class="form-select border-2 add-donvi">
                        <option value="0">--- Chọn đơn vị ---</option>
                        @foreach($dsdonvi as $dsdv)
                            <option value="{{$dsdv->id}}">{{$dsdv->ten_don_vi}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small">Mật khẩu mặc định</label>
                    <input type="password" class="form-control border-2 add-password" name="password" value="123">
                    <small class="text-muted italic">Mặc định là: 123</small>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold add-submit shadow-sm">Tạo tài khoản</button>
                    <button type="button" class="btn btn-light btn-lg" data-bs-dismiss="offcanvas">Hủy</button>
                </div>
            </form>
        </div>
    </div>
