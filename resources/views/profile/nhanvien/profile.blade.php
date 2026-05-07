<div class="tab-pane fade show active" id="profile">
    <div class="row g-4">
        <div class="col-lg-4 col-md-5 mt-3">
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

                <div class="card border-0 shadow-sm rounded-3 bg-primary text-white">
                    <div class="card-body p-4 text-center">
                        <h6 class="text-uppercase small opacity-75 mb-3">Hiệu suất năm {{ $nh->ten_nam_hoc ?? '' }}</h6>
                        <div class="display-5 fw-bold mb-1">{{ $phantramtong }}%</div>
                        <p class="small mb-3">Đã hoàn thành {{ $tongdatduoc }}/{{ $tongchitieu }} chỉ tiêu</p>
                        <div class="progress bg-white bg-opacity-25" style="height: 6px;">
                            <div class="progress-bar bg-white" style="width: {{ $phantramtong }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-7">        
            @if($dscv_dahoanthanh->count() > 0)
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden mt-2">
                    <div class="card-header bg-light border-0 py-3">
                        <h6 class="fw-bold text-uppercase small text-success mb-0">
                            <i class="bi bi-patch-check-fill me-2"></i>Danh mục KPI đã hoàn thành
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @if($dscv_dahoanthanh->isNotEmpty())
                                @foreach($dscv_dahoanthanh as $cv)
                                    <div class="list-group-item list-group-item-action border-0 py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="bi bi-check2-circle text-success fs-5"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="mb-0 fw-semibold text-dark" style="font-size: 0.85rem; line-height: 1.4;">
                                                    {{ $cv->ten_kpi }}
                                                </p>
                                                <small class="text-muted" style="font-size: 0.75rem;">Hoàn thành lúc: {{ $cv->updated_at->format('d/m/Y') }}</small>
                                            </div>
                                            <form action="{{route('qlcongviec.xemlsbaocao',  $cv->id )}}" method="GET">
                                                <button type="submit" class="btn btn-sm btn-outline-primary mt-2" >Xem lịch sử</button>
                                            </form>
                                        </div>                       
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @else
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mt-2">
                    <div class="card-header bg-light border-0 py-3">
                        <h6 class="fw-bold text-uppercase small text-success mb-0">
                            <i class="bi bi-patch-check-fill me-2"></i>Danh mục KPI đã hoàn thành
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <span class="text-muted p-4">Chưa có KPI hoàn thành</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@push('script')
    <script src="{{ asset('js/thongbaoxacnhan.js') }}"></script>
  @endpush