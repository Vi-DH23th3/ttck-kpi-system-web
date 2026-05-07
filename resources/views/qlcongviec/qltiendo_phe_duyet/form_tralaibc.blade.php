<div class="modal fade" id="modalTraLai" tabindex="-1" aria-labelledby="modalTraLaiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalTraLaiLabel"><i class="bi bi-file-earmark-arrow-up me-2"></i>Trả lại</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body body-tra-lai">
                <div class="mb-3">
                    <label for="ghi_chu_tl" class="form-label">Lý do trả lại</label>
                </div>
                <form action="{{route('manager.qlcongviec.qltiendo.tralai')}}" method="POST">
                    @csrf
                    <input type="hidden" name="bao_cao_cong_viec_id" class="tlbc">
                    <textarea class="form-control" rows="3" name="ghi_chu_tl"></textarea>
                    <button type="button" class="btn btn-danger mt-3" onclick="xacNhan(this)" data-message="Xác nhận trả lại báo cáo này?">
                    Trả lại
                    </button>
                </form>
            </div>
            <div class="modal-footer bg-light mt-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div> 