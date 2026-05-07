$(".btn_edit_namhoc").on("click", function () {
    var namhocId = $(this).data("namhoc-id");
    var tenNamHoc = $(this).data("ten-nh");
    var ngayBatDau = $(this).data("ngay-bat-dau");
    var ngayKetThuc = $(this).data("ngay-ket-thuc");

    $(".edit-name").val(tenNamHoc);
    $(".edit-start-date").val(ngayBatDau);
    $(".edit-end-date").val(ngayKetThuc);
    $(".edit-id").val(namhocId);
    $(".idnh").text("ID năm học: " + namhocId);
    var myOffcanvas = new bootstrap.Offcanvas($("#offcanvasEditNamHoc")[0]);
    myOffcanvas.show();
});
$(".edit-submit").click(function (e) {
    e.preventDefault();
    // let namhocId = $(this).data("namhoc-id");

    let id = $(this).data("namhoc-id") || $(".edit-id").val();
    $(".edit-id").val(id);
    Swal.fire({
        icon: "info",
        title: "Xác nhận",
        confirm: true,
        text: "Xác nhận cập nhật năm học!",
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.showLoading();

            $.ajax({
                //_token: $('meta[name="csrf-token"]').attr("content"),
                url: "/admin/namhoc/" + id,
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                    _method: "PUT",
                    ten_nam_hoc: $(".edit-name").val(),
                    ngay_bat_dau: $(".edit-start-date").val(),
                    ngay_ket_thuc: $(".edit-end-date").val(),
                },
                success: function (response) {
                    document.activeElement.blur();
                    let offcanvasElement = document.getElementById(
                        "offcanvasEditNamHoc",
                    );
                    let instance =
                        bootstrap.Offcanvas.getInstance(offcanvasElement);
                    if (instance) {
                        instance.hide();
                    }
                    Swal.fire({
                        icon: "success",
                        title: "Thành công",
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false,
                        timerProgressBar: true,
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = "";

                        $.each(errors, function (key, value) {
                            errorMessage += value[0];
                        });

                        Swal.fire({
                            icon: "error",
                            title: "Lỗi",
                            text: errorMessage,
                            showConfirmButton: true,
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Thất bại",
                            text: "Có lỗi hệ thống xảy ra!",
                        });
                    }
                },
            });
        }
    });
});
