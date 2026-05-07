$(document).ready(function () {
    $(".btn_edit_chucvu").click(function (e) {
        let url = $(this).data("url");

        $.ajax({
            url: url,
            method: "GET",
            success: function (response) {
                $(".edit-name").val(response.chucvu.ten_chuc_vu);

                let actionUrl = "/admin/chucvu/" + response.chucvu.id;
                $(".form-edit").attr("action", actionUrl);

                var myOffcanvas = new bootstrap.Offcanvas(
                    $("#offcanvasEditChucVu")[0],
                );
                myOffcanvas.show();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            },
        });
    });
});
