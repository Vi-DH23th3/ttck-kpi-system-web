$(document).ready(function () {
    $(".btn_edit_donvi").click(function (e) {
        let donviId = $(this).data("donvi-id");
        $.ajax({
            url: "/admin/donvi/" + donviId + "/edit",
            method: "GET",
            success: function (response) {
                $(".edit-name").val(response.donvi.ten_don_vi);
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
    $(document)
        .off("click", ".edit-submit")
        .on("click", ".edit-submit", function (e) {
            e.preventDefault();
            let donviId = $(this).data("donvi-id");
            Swal.fire({
                icon: "info",
                title: "Xác nhận",
                confirm: true,
                text: "Xác nhận cập nhật đơn vị!",
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.showLoading();

                    $.ajax({
                        url: "/admin/donvi/" + donviId,
                        method: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr(
                                "content",
                            ),
                            _method: "PUT",
                            ten_don_vi: $("#edit-name").val(),
                        },
                        success: function (response) {
                            let offcanvasElement =
                                document.getElementById("offcanvasEditDonvi");
                            let instance =
                                bootstrap.Offcanvas.getInstance(
                                    offcanvasElement,
                                );
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
                                // Lấy lỗi từ Laravel Validation trả về
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
});
