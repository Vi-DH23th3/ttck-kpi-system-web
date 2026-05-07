document.addEventListener("DOMContentLoaded", function () {
    // 1. Hiện/ẩn cấu hình bù
    const cbBu = document.getElementById("cho_phep_bu");
    const wrapperBu = document.getElementById("nguong_bu_wrapper");
    cbBu.addEventListener("change", function () {
        wrapperBu.style.display = this.checked ? "block" : "none";
    });

    // 2. Hiện/ẩn section Đa chỉ tiêu
    const selectLoai = document.getElementById("loai_kpi");
    const sectionDaChiTieu = document.getElementById("section_da_chi_tieu");
    selectLoai.addEventListener("change", function () {
        sectionDaChiTieu.style.display =
            this.value === "da_chi_tieu" ? "block" : "none";
    });

    const container = document.getElementById("wrapper_da_chi_tieu");
    const btnAdd = document.getElementById("btn-add-dct");
    const template = document.getElementById("da-chi-tieu-template");

    btnAdd.addEventListener("click", function () {
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    });
    // 2. Hàm xóa dòng
    container.addEventListener("click", function (e) {
        if (e.target.classList.contains("btn-remove-dct")) {
            // Tìm đến thẻ div.rule-row gần nhất để xóa
            e.target.closest(".rule-row").remove();
        }
    });
});
