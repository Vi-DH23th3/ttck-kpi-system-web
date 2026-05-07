<div class="sticky-top" style="top: 100px; z-index: 10;">
    <div class="card border-0 shadow-sm mb-4 mt-3 rounded-3">
        <div class="card-body p-4">
            <div class="profile-info">
                <h6 class="fw-bold text-uppercase small text-primary mb-3">Thông tin chi tiết</h6>
                <div class="mb-3 d-flex justify-content-between">
                    <span class="text-muted small"><i class="bi bi-building me-2"></i>Phòng ban:</span>
                    <span class="fw-semibold small">{{ Auth::user()->donVi->ten_don_vi ?? '' }}</span>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <span class="text-muted small"><i class="bi bi-calendar-check me-2"></i>Ngày tham gia:</span>
                    <span class="fw-semibold small">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small"><i class="bi bi-info-circle me-2"></i>Trạng thái:</span>
                    <span class="badge {{ Auth::user()->trang_thai == 1 ? 'bg-success' : 'bg-danger' }} rounded-pill" style="font-size: 10px;">
                        {{ Auth::user()->trang_thai == 1 ? 'Hoạt động' : 'Khóa' }}
                    </span>
                </div>
            </div>

            <div class="edit-profile d-none mt-3">
                <h6 class="fw-bold text-uppercase small text-primary mb-3">Cập nhật thông tin</h6>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="mb-2">
                        <label class="small fw-bold">Thay ảnh đại diện</label>
                        <input type="file" class="form-control form-control-sm" name="avatar">
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control form-control-sm" value="{{ Auth::user()->name }}" name="name" placeholder="Họ tên">
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control form-control-sm" value="{{ Auth::user()->email }}" name="email" placeholder="Email">
                    </div>
                    <button type="button" class="btn btn-sm btn-primary w-100" onclick="xacNhan(this)" data-message="Xác nhận cập nhật thông tin?">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>
