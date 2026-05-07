<div class="modal fade" id="modalNopBaoCao" tabindex="-1" aria-labelledby="modalNopBaoCaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalNopBaoCaoLabel">
                    <i class="bi bi-file-earmark-arrow-up me-2"></i>Nộp Báo Cáo Tiến Độ
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formNopBaoCao" action="{{ route('staff.profile.storebaocao') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-secondary py-2 mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="d-block">KPI: <strong id="display_ten_kpi" class="text-dark">...</strong></small>
                            </div>
                            <div class="col-md-6">
                                <small class="d-block text-md-end">Chỉ tiêu: <strong id="display_chi_tieu">...</strong></small>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="phan_cong_cong_viec_id" id="input_phan_cong_cong_viec_id">

                    <div class="row g-4">
                        <div class="col-md-6 border-end">
                            <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Thông tin thực hiện</h6>
                            
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Ngày báo cáo</label>
                                    <input type="date" name="ngay_bao_cao" class="form-control form-control-lg" value="{{ date('Y-m-d') }}" readonly>
                                </div>
                                <div class="col-12">
                                    <div id="da_chi_tieu" class="bg-light p-2 rounded">
                                        </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Tài liệu đính kèm</label>
                                    <input type="file" name="file_minh_chung" class="form-control">
                                    <div class="form-text">PDF, Word, Excel, Hình ảnh (Max 5MB)</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Mô tả công việc đã làm</h6>            
                            <textarea name="ghi_chu" class="form-control h-75" rows="5" placeholder="Mô tả chi tiết công việc đã làm..."></textarea>
                        </div>  
                    </div>
                </div>

                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary px-4 shadow" onclick="xacNhan(this)" data-message="Xác nhận nộp báo cáo?">
                        <i class="bi bi-send me-1"></i> Gửi báo cáo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>