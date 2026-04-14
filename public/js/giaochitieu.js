document.addEventListener("DOMContentLoaded", function () {
    // 1. Thêm điều kiện mới
    const container = document.getElementById("dynamic-rules-container");
    const btnAdd = document.getElementById("add-rule-btn");
    const template = document.getElementById("rule-row-template");

    // 1. Hàm thêm dòng mới
    btnAdd.addEventListener("click", function () {
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    });

    // 2. Hàm xóa dòng (Dùng Event Delegation)
    container.addEventListener("click", function (e) {
        if (e.target.classList.contains("btn-remove-rule")) {
            // Tìm đến thẻ div.rule-row gần nhất để xóa
            e.target.closest(".rule-row").remove();
        }
    });
    // DMCV SELECT
    let defaultHtml =
        document.getElementById("kpi-datalist-container")?.innerHTML || "";

    document
        .getElementById("dmcvSelector")
        ?.addEventListener("input", function () {
            let val = this.value;

            let option = $("#dmcvTemplates option").filter(function () {
                return $(this).val() === val;
            });

            if (option.length) {
                let dmid = option.data("dmcv");

                document.getElementById("target_dmcv_id").value = dmid;

                $.ajax({
                    url: "/giaochitieu",
                    method: "GET",
                    data: { dm_cv_id: dmid },
                    success: function (response) {
                        document.getElementsByClassName("input-dmcv")[0].value =
                            response.tvdm.ten_cong_viec;

                        let html = "";
                        response.tvkpi.forEach(function (item) {
                            html += `
                        <option value="${item.ten_kpi} | #${item.id}" 
                            data-id="${item.id}" 
                            data-chitieu="${item.chi_tieu}" 
                            data-donvi="${item.don_vi}" 
                            data-chuky="${item.chu_ky}" 
                            data-realname="${item.ten_kpi}">
                            Mẫu: ${item.chi_tieu} ${item.don_vi}
                        </option>
                    `;
                        });

                        document.getElementById(
                            "kpi-datalist-container",
                        ).innerHTML =
                            `<datalist id="kpiTemplates">${html}</datalist>`;
                    },
                });

                document
                    .querySelectorAll(".dmcv-phongban")
                    .forEach((el) => el.classList.add("d-none"));
            } else {
                document.getElementById("target_dmcv_id").value = "";
                document.getElementById("kpi-datalist-container").innerHTML =
                    defaultHtml;

                document
                    .querySelectorAll(".dmcv-phongban")
                    .forEach((el) => el.classList.remove("d-none"));
            }
        });

    // KPI SELECT
    document.addEventListener("input", function (event) {
        if (event.target && event.target.id === "kpiSelector") {
            let val = event.target.value;

            document.getElementById("target_ten_kpi").value = val;

            //  Array + find do trên NodeList không có method find trực tiếp
            let option = Array.from(
                document.querySelectorAll("#kpiTemplates option"),
            ).find((opt) => opt.value === val);

            if (option) {
                document.getElementById("target_kpi_id").value =
                    option.dataset.id;
                document.getElementById("target_chi_tieu").value =
                    option.dataset.chitieu;
                document.getElementById("target_don_vi").value =
                    option.dataset.donvi;
                document.getElementById("target_chu_ky").value =
                    option.dataset.chuky;
                document.getElementById("target_ten_kpi").value =
                    option.dataset.realname;
            } else {
                document.getElementById("target_kpi_id").value = "";
            }
        }
    });

    // SELECT ALL USER
    document
        .getElementById("selectAll")
        ?.addEventListener("click", function () {
            document.querySelectorAll(".user-checkbox").forEach((el) => {
                el.checked = this.checked;
            });
        });

    // SEARCH USER
    document
        .getElementById("search-user-chitieu")
        ?.addEventListener("click", function (e) {
            e.preventDefault();

            $.ajax({
                url: "/giaochitieu",
                method: "GET",
                data: {
                    usersearch: document.getElementById("userSearch").value,
                },
                success: function (response) {
                    document.getElementById(
                        "giaochitieu-table-user",
                    ).innerHTML = response.html;
                },
                error: function (xhr) {
                    alert("Lỗi: " + xhr.status);
                    console.log(xhr.responseText);
                },
            });
        });

    // KPI THỦ CÔNG
    document.querySelectorAll('input[name="loai_kpi"]').forEach((radio) => {
        radio.addEventListener("change", function () {
            const config = document.getElementById("nang_cao_config");

            if (!config) return;

            if (this.value === "nang_cao") {
                config.classList.remove("d-none");
            } else {
                config.classList.add("d-none");
            }
        });
    });

    document
        .getElementById("cho_phep_bu")
        ?.addEventListener("change", function () {
            const nguong = document.getElementById("nguong_bu_container");

            if (!nguong) return;

            nguong.style.display = this.checked ? "block" : "none";
        });
});
