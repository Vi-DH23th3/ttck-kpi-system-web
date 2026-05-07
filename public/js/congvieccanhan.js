let profile = document.querySelector(".profile-info");
let edit_profile = document.querySelector(".edit-profile");
let btnEdit = document.querySelector(".btn-edit");

btnEdit.addEventListener("click", function () {
    if (edit_profile.classList.contains("d-none")) {
        profile.classList.add("d-none");
        edit_profile.classList.remove("d-none");
        this.innerHTML = '<i class="bi bi-x-circle me-1"></i> Hủy sửa';
        this.classList.replace("btn-outline-primary", "btn-outline-danger");
    } else {
        profile.classList.remove("d-none");
        edit_profile.classList.add("d-none");
        this.innerHTML =
            '<i class="bi bi-pencil-square me-1"></i> Chỉnh sửa hồ sơ';
        this.classList.replace("btn-outline-danger", "btn-outline-primary");
    }
});
const btnBaocaos = document.querySelectorAll(".btn-baocao");

btnBaocaos.forEach(function (btn) {
    btn.addEventListener("click", function () {
        let id = this.dataset.id;
        let ten = this.dataset.ten;
        let chitieu = this.dataset.chitieu;
        let dieukienRaw = this.dataset.dieukien;
        let loaiKPI = this.dataset.loaikpi;
        let donVi = this.dataset.donvi;
        let chuKy = this.dataset.chuky;
        let container = document.getElementById("da_chi_tieu");
        container.innerHTML = "";
        if (loaiKPI === "da_chi_tieu") {
            if (dieukienRaw && dieukienRaw !== "null") {
                let dieukienArray = JSON.parse(dieukienRaw);

                let container = document.getElementById("da_chi_tieu");
                container.innerHTML = "";

                dieukienArray.forEach((item) => {
                    let inputKey = item.key;

                    let labelHienThi = item.ten;
                    let valueYeuCau = item.gia_tri;
                    let toanTu = String(item.toan_tu || "").trim();
                    let phamVi = item.pham_vi;

                    let html = `
                    <div class="col-12 mb-3">
                        
                        <label class="form-label fw-bold">
                            ${labelHienThi}
                            <span class="text-primary small">
                                (Yêu cầu: ${toanTu} ${Number(valueYeuCau).toLocaleString()})
                            </span>

                            ${
                                phamVi === "tat_ca"
                                    ? '<span class="badge bg-info-subtle text-info ms-1" style="font-size:10px">Tổng lũy kế</span>'
                                    : '<span class="badge bg-secondary-subtle text-secondary ms-1" style="font-size:10px">Theo báo cáo</span>'
                            }
                        </label>

                        <input type="number"
                            name="gia_tri_thuc_te[${inputKey}]"
                            class="form-control"
                            placeholder="Nhập số ${labelHienThi.toLowerCase()}"
                            required>
                    </div>
                `;

                    container.insertAdjacentHTML("beforeend", html);
                });
            }
        } else if (loaiKPI === "don_gian") {
            let html = `
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold">
                        Giá trị thực hiện
                    </label>

                    <input type="number"
                        name="tien_do"
                        class="form-control"
                        placeholder="Nhập ${donVi}"
                        required>
                </div>
            `;

            container.insertAdjacentHTML("beforeend", html);
        } else if (loaiKPI === "nang_cao") {
            let html = `
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold">
                        Tiến độ thực hiện (%)
                    </label>

                    <input type="number"
                        name="tien_do"
                        class="form-control"
                        min="0"
                        max="100"
                        placeholder="Nhập % hoàn thành"
                        required>
                </div>
            `;

            container.insertAdjacentHTML("beforeend", html);
        }

        document.getElementById("display_ten_kpi").textContent = ten;
        if (loaiKPI === "da_chi_tieu") {
            document.getElementById("display_chi_tieu").textContent =
                "Nhiều chỉ tiêu";
        } else {
            document.getElementById("display_chi_tieu").textContent =
                chitieu + donVi + "/" + chuKy;
        }
        document.getElementById("input_phan_cong_cong_viec_id").value = id;
        //  document.getElementById("don_vi_tinh").value = donvi;
        var myModal = new bootstrap.Modal(
            document.getElementById("modalNopBaoCao"),
        );
        myModal.show();
    });
});
