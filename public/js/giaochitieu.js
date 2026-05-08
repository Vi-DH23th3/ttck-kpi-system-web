document.addEventListener("DOMContentLoaded", function () {
    //ĐA CHỈ TIÊU
    // Thêm chỉ tiêu
    const container = document.getElementById("dynamic-rules-container");
    const btnAdd = document.getElementById("add-rule-btn");
    const template = document.getElementById("rule-row-template");

    btnAdd.addEventListener("click", function () {
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    });
    //Hàm xóa dòng
    container.addEventListener("click", function (e) {
        if (e.target.classList.contains("btn-remove-rule")) {
            e.target.closest(".rule-row").remove();
        }
    });

    document
        .getElementById("kpiSelector")
        .addEventListener("change", function () {
            let value = this.value;

            let option = Array.from(
                document.querySelectorAll("#kpiTemplates option"),
            ).find((opt) => opt.value == value);

            if (!option) return;

            // KPI ID
            document.getElementById("target_kpi_id").value = option.value;

            // KPI info
            document.getElementById("target_ten_kpi").value =
                option.dataset.ten;
            document.getElementById("target_chi_tieu").value =
                option.dataset.chitieu;
            document.getElementById("target_don_vi").value =
                option.dataset.donvi;
            document.getElementById("target_chu_ky").value =
                option.dataset.chuky;

            document.getElementById("dmcvSelector").value =
                option.dataset.tendm;
            document.getElementById("target_dmcv_id").value =
                option.dataset.iddm;
            document.getElementById("ghi_chu").value = option.dataset.ghichu;
        });
    // SELECT ALL USER
    document
        .getElementById("selectAll")
        ?.addEventListener("click", function () {
            document.querySelectorAll(".user-checkbox").forEach((el) => {
                el.checked = this.checked;
            });
        });
    //XEM REVIEW TRƯỚC KHI GIAO KPI
    document
        .getElementById("btn-review")
        .addEventListener("click", function () {
            let ten = document.getElementById("kpiSelector").value;
            let chiTieu = document.getElementById("target_chi_tieu").value;
            let donVi = document.getElementById("target_don_vi").value;
            let chuKy = document.getElementById("target_chu_ky").value;

            let loai = document.querySelector(
                "input[name='loai_kpi']:checked",
            )?.value;

            let ngayBD = document.querySelector("[name='ngay_bat_dau']").value;
            let ngayKT = document.querySelector("[name='ngay_ket_thuc']").value;

            let mucDo = document.querySelector("[name='muc_do']").value;
            let ghiChu = document.querySelector("[name='ghi_chu']").value;

            //2. Render cơ bản
            document.getElementById("preview-ten_kpi").innerText =
                ten || "Chưa nhập";
            document.getElementById("preview-chi_tieu").innerText =
                chiTieu || 0;
            document.getElementById("preview-don_vi").innerText = donVi || "";
            document.getElementById("preview-chu_ky").innerText = chuKy || "";

            let text = "Đơn giản";

            if (loai === "nang_cao") {
                text = "Nâng cao";
            } else if (loai === "da_chi_tieu") {
                text = "Đa chỉ tiêu";
            }

            document.getElementById("preview-loai_kpi").innerText = text;

            document.getElementById("preview-ngay_bat_dau").innerText = ngayBD;
            document.getElementById("preview-ngay_ket_thuc").innerText = ngayKT;
            document.getElementById("preview-muc_do").innerText = mucDo;
            document.getElementById("preview-ghi_chu").innerText = ghiChu;

            let html = "";

            if (loai === "nang_cao") {
                //KPI nâng cao
                let soLan = document.querySelector(
                    "[name='so_lan_toi_thieu_thang']",
                ).value;
                let chuKyThang = document.querySelector(
                    "[name='chu_ky_thang']",
                ).value;

                if (soLan && chuKyThang) {
                    html += `
            <div class="alert alert-info">
                Tần suất: tối thiểu ${soLan} lần / ${chuKyThang} tháng
            </div>`;
                }
                //Phần cho phép bù
                let choBu = document.getElementById("cho_phep_bu").checked;
                let nguong = document.querySelector(
                    "[name='nguong_duoc_bu']",
                ).value;

                if (choBu) {
                    html += `
            <div class="alert alert-warning">
                Cho phép bù khi đạt ≥ ${nguong}%
            </div>`;
                }
            }
            if (loai === "da_chi_tieu") {
                let tenDK = document.getElementsByName("da_chi_tieu_ten[]");
                let toanTu = document.getElementsByName("toan_tu[]");
                let giaTri = document.getElementsByName(
                    "da_chi_tieu_gia_tri[]",
                );
                let phamVi = document.getElementsByName("pham_vi[]");
                for (let i = 0; i < tenDK.length; i++) {
                    if (!tenDK[i].value) continue;

                    let pv =
                        phamVi[i].value === "tat_ca"
                            ? "Toàn KPI"
                            : "Theo từng báo cáo";

                    html += `
            <div class="border p-2 mb-2 rounded bg-white">
                <b>${tenDK[i].value}</b><br>
                <span class="text-muted">
                    ${toanTu[i].value} ${giaTri[i].value} (${pv})
                </span>
            </div>`;
                }
            }

            document.getElementById("preview-rules").innerHTML = html;
        });
    function renderRules(rules) {
        const container = document.getElementById("dynamic-rules-container");
        const template = document.getElementById("rule-row-template");

        container.innerHTML = "";

        rules.forEach((rule) => {
            const clone = template.content.cloneNode(true);

            const row = clone.querySelector(".rule-row");

            row.querySelector('input[name="da_chi_tieu_ten[]"]').value =
                rule.ten || "";
            row.querySelector('select[name="toan_tu[]"]').value =
                rule.toan_tu || ">=";
            row.querySelector('input[name="da_chi_tieu_gia_tri[]"]').value =
                rule.gia_tri || "";
            row.querySelector('select[name="pham_vi[]"]').value =
                rule.pham_vi || "tat_ca";
            row.querySelector('select[name="don_vi_dct[]"]').value =
                rule.pham_vi || "don_vi";
            row.querySelector('select[name="chu_ky_dct[]"]').value =
                rule.pham_vi || "chu_ky";
            container.appendChild(clone);
        });
    }
    document.querySelectorAll(".btn-giao").forEach((btn) => {
        btn.addEventListener("click", function () {
            let index = this.dataset.index;
            $.ajax({
                url: "/manager/giaochitieu/" + index,
                method: "GET",
                success: function (res) {
                    // console.log(res);
                    let formData = {
                        index: index,
                        danh_muc: res.danh_muc,
                        ten_kpi: res.ten_kpi,
                        chi_tieu: res.chi_tieu,
                        don_vi: res.don_vi,
                        chu_ky: res.chu_ky,
                        ghi_chu: res.ghi_chu,
                        dieu_kien: res.dieu_kien,
                    };

                    localStorage.setItem("kpi_form", JSON.stringify(formData));
                    fillForm(res, index);

                    let tabEl = document.querySelector(
                        '[data-bs-target="#thucong"]',
                    );
                    if (tabEl) {
                        new bootstrap.Tab(tabEl).show();
                    }
                },
            });
        });
    });
    function fillForm() {
        //LOAD KPI
        let data = localStorage.getItem("kpi_form");
        if (data) {
            let res = JSON.parse(data);

            document.getElementById("session_index").value = res.index || "";
            document.getElementById("dmcvSelector").value = res.danh_muc || "";
            document.getElementById("kpiSelector").value = res.ten_kpi || "";
            document.getElementById("target_ten_kpi").value = res.ten_kpi || "";
            document.getElementById("target_chi_tieu").value =
                res.chi_tieu ?? "";
            document.getElementById("target_don_vi").value = res.don_vi || "";
            document.getElementById("target_chu_ky").value = res.chu_ky || "";
            document.getElementById("ghi_chu").value = res.ghi_chu || "";

            if (res.don_vi == "tham_chieu") {
                Swal.fire({
                    icon: "info",
                    title: "KPI tham chiếu",
                    text: "KPI này thuộc dạng tham chiếu (không có chỉ tiêu cố định). Vui lòng khai báo các điều kiện KPI tương ứng.",
                    showConfirmButton: true,
                });
            }
            if (res.don_vi === "multi") {
                let radio = document.querySelector(
                    'input[name="loai_kpi"][value="da_chi_tieu"]',
                );
                if (radio) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event("change"));
                }
            }

            renderRules(res.dieu_kien || []);
        }
    }
    document.querySelectorAll('input[name="loai_kpi"]').forEach((el) => {
        el.addEventListener("change", function () {
            let form_tt = document.getElementById("tt_chi_tieu");
            let tt_review = document.getElementById("tt_review");
            let daChiTieu = document.getElementById("daChiTieu");
            let kpiTheoCK = document.getElementById("chuKyKPI");

            if (this.value === "da_chi_tieu") {
                form_tt.classList.add("d-none");
                tt_review.classList.add("d-none");
                kpiTheoCK.classList.add("d-none");
                document
                    .getElementById("preview-rules")
                    .classList.remove("d-none");
                daChiTieu.classList.remove("d-none");

                document
                    .getElementById("target_chi_tieu")
                    .classList.add("d-none");
                document
                    .getElementById("target_don_vi")
                    .classList.add("d-none");
                document
                    .getElementById("target_chu_ky")
                    .classList.add("d-none");
            } else if (this.value === "nang_cao") {
                form_tt.classList.remove("d-none");
                tt_review.classList.remove("d-none");
                kpiTheoCK.classList.remove("d-none");
                document
                    .getElementById("target_chi_tieu")
                    .classList.remove("d-none");
                document
                    .getElementById("target_don_vi")
                    .classList.remove("d-none");
                document
                    .getElementById("target_chu_ky")
                    .classList.remove("d-none");

                daChiTieu.classList.add("d-none");
            } else {
                form_tt.classList.remove("d-none");
                tt_review.classList.remove("d-none");
                kpiTheoCK.classList.add("d-none");
                document
                    .getElementById("target_chi_tieu")
                    .classList.remove("d-none");
                document
                    .getElementById("target_don_vi")
                    .classList.remove("d-none");
                document
                    .getElementById("target_chu_ky")
                    .classList.remove("d-none");

                daChiTieu.classList.add("d-none");
            }
        });
    });
});
