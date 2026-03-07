$(document).ready(function () {
    $(".btn_edit_donvi").click(function (e) {
        let donviId = $(this).data("donvi-id");
        $.ajax({
            url: "/donvi/" + donviId + "/edit",
            method: "GET",
            success: function (response) {
                $(".edit-name").val(response.donvi.ten_don_vi);
                //gán userId vào nút submit để sử dụng khi cập nhật
                $(".edit-submit").data("donvi-id", response.donvi.id);
                var myOffcanvas = new bootstrap.Offcanvas(
                    $("#offcanvasEditDonvi")[0],
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
        let donviId = $(this).data("donvi-id");
        $.ajax({
            //_token: $('meta[name="csrf-token"]').attr("content"),
            url: "/donvi/" + donviId,
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                _method: "PUT",
                ten_don_vi: $("#edit-name").val(),
            },
            success: function (response) {
                let offcanvasElement =
                    document.getElementById("offcanvasEditDonvi");
                let instance =
                    bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (instance) {
                    instance.hide();
                }
                Swal.fire({
                    icon: "success",
                    title: "Thành công",
                    text: "Cập nhật phòng ban thành công!",
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
