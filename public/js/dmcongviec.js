$(document).ready(function () {
    $(".btn_edit_cv").click(function (e) {
        let dmcongviecId = $(this).data("cv-id");
        $.ajax({
            url: "/system/qlcongviec/dmcongviec/" + dmcongviecId + "/edit",
            method: "GET",
            success: function (response) {
                console.log(response);
                $(".edit-name").val(response.dmcv.ten_cong_viec);
                $("#formUpdateDMCV-listListForm").attr(
                    "action",
                    "/system/qlcongviec/dmcongviec/" + dmcongviecId,
                );

                let dmSelect = $("#dmEditSelect");
                dmSelect.empty();
                dmSelect.append(
                    '<option value="0">--- Chọn đơn vị ---</option>',
                );
                if (response.allDonVi) {
                    response.allDonVi.forEach(function (donVi) {
                        let selected =
                            donVi.id === response.dmcv.don_vi_id
                                ? "selected"
                                : "";

                        dmSelect.append(
                            `<option value="${donVi.id}" ${selected}>${donVi.ten_don_vi}</option>`,
                        );
                    });
                }

                var el = document.getElementById("offcanvasEditDMCV");
                var myOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
                myOffcanvas.show();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            },
        });
    });
});
