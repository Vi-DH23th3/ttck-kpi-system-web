$(document).ready(function () {
    $(".btn_edit_cv").click(function (e) {
        let dmcongviecId = $(this).data("cv-id");
        // console.log(dmcongviecId);
        $.ajax({
            url: "/qlcongviec/dmcongviec/" + dmcongviecId + "/edit",
            method: "GET",
            success: function (response) {
                $(".edit-name").val(response.dmcv.ten_cong_viec);
                // $(".id-cv").val(response.dmcv.id);
                $("#formUpdateDMCV-listListForm").attr(
                    "action",
                    "/qlcongviec/dmcongviec/" + dmcongviecId,
                );
                //gán userId vào nút submit để sử dụng khi cập nhật
                // $(".id_hidden").data("cv-id", response.dmcv.id);
                var myOffcanvas = new bootstrap.Offcanvas(
                    $("#offcanvasEditDMCV")[0],
                );
                myOffcanvas.show();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            },
        });
    });
});
