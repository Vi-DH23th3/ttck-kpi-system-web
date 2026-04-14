document.addEventListener("DOMContentLoaded", function () {
    // KPI FILE (MULTI ROW)
    document.addEventListener("change", function (e) {
        const target = e.target;

        // 3. Radio chọn Loại hình KPI
        if (target.classList.contains("loai-kpi-radio")) {
            const row = target.closest(".kpi-row");
            const config = row.querySelector(".nang_cao_config");
            if (target.value === "nang_cao") {
                config.classList.remove("d-none");
            } else {
                config.classList.add("d-none");
            }
        }

        // 4. Checkbox cho phép bù nợ
        if (target.classList.contains("cho_phep_bu")) {
            const row = target.closest(".kpi-row");
            const nguong = row.querySelector(".nguong_bu_container");
            if (target.checked) {
                nguong.classList.remove("d-none");
            } else {
                nguong.classList.add("d-none");
            }
        }
    });
    document.addEventListener("click", function (event) {
        const addBtn = event.target.closest(".add-rule-btn");

        if (addBtn) {
            event.preventDefault();

            const row = addBtn.closest(".kpi-row");
            const rowIndex = row.getAttribute("data-index");
            const container = row.querySelector(".dynamic-rules-container");
            const template = document.getElementById("rule-row-template");

            let wrapper = document.createElement("div");
            wrapper.innerHTML = template.innerHTML;

            wrapper.querySelectorAll("input").forEach((input) => {
                const field = input.dataset.field;

                input.name = `tasks[${rowIndex}][${field}][]`;
            });

            container.appendChild(wrapper.firstElementChild);
        }

        // nút X
        if (event.target.classList.contains("btn-remove-rule")) {
            event.target.closest(".rule-row").remove();
        }
    });
    // ADD USER KPI
    $(document).ready(function () {
        $(document).on("click", ".btn-add-user-kpi", function () {
            let index = $(this).data("index");

            $("#kpi-index").val(index);
            $("#display-index").text(index);

            $(".user-checkbox").prop("checked", false);
        });

        document
            .getElementById("submit-user-kpi")
            ?.addEventListener("click", function () {
                //  data-name="{{ $user->name }}"
                console.log(this.dataset.name);
                let index = document.getElementById("kpi-index").value;
                let td = document.querySelector(
                    `td[data-kpi-users="${index}"]`,
                );

                let html = "";
                document
                    .querySelectorAll(".user-checkbox:checked")
                    .forEach((checkbox) => {
                        let userId = checkbox.value;
                        let userName = checkbox.dataset.name;
                        // document
                        //     .querySelectorAll(".user-checkbox:checked")
                        //     .forEach(function () {
                        //         let userId = this.value;
                        //         let userName = this.dataset.name;

                        html += `
                <div class="badge bg-info text-white p-2 d-flex align-items-center gap-1">
                    ${userName}
                    <input type="hidden" name="tasks[${index}][user_ids][]" value="${userId}">
                    <i class="bi bi-x-circle text-danger cursor-pointer" onclick="this.parentElement.remove()"></i>
                </div>
            `;
                    });

                if (td) td.innerHTML = html;

                let offcanvasElement = document.getElementById(
                    "offcanvasAddUserKPI",
                );
                let instance =
                    bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (instance) instance.hide();
            });
    });
});
