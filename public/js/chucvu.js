$(document).ready(function () {
    $(".btn_edit_chucvu").click(function (e) {
        let chucvuId = $(this).data("chucvu-id");
        $.ajax({
            url: "/chucvu/" + chucvuId + "/edit",
            method: "GET",
            success: function (response) {
                $(".edit-name").val(response.chucvu.ten_chuc_vu);
                //gán userId vào nút submit để sử dụng khi cập nhật
                $(".edit-submit").data("chucvu-id", response.chucvu.id);
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
    $(".edit-submit").click(function (e) {
        e.preventDefault();
        let chucvuId = $(this).data("chucvu-id");
        $.ajax({
            //_token: $('meta[name="csrf-token"]').attr("content"),
            url: "/chucvu/" + chucvuId,
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                _method: "PUT",
                ten_chuc_vu: $("#edit-name").val(),
            },
            success: function (response) {
                let offcanvasElement = document.getElementById(
                    "offcanvasEditChucVu",
                );
                let instance =
                    bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (instance) {
                    instance.hide();
                }
                Swal.fire({
                    icon: "success",
                    title: "Thành công",
                    text: "Cập nhật chức vụ thành công!",
                    timer: 3000,
                    showConfirmButton: false,
                    timerProgressBar: true,
                }).then(() => {
                    window.location.reload();
                });
            },
        });
    });
});
