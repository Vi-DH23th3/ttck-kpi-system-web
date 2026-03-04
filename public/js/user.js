$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$(document).ready(function () {
    console.log("Script đã nạp thành công");
    function fetchUser() {
        let pagrams = new URLSearchParams(window.location.search); // Sử dụng URLSearchParams để lấy tham số từ URL
        let currentPage = pagrams.get("page") || 1; // Lấy giá trị của tham số 'page' từ URL, nếu không có thì mặc định là 1
        $.ajax({
            url: "/users?page=" + currentPage,
            method: "GET",
            success: function (response) {
                $(".table-user").html(response); // Cập nhật lại phần table với dữ liệu mới
            },
        });
    }

    // Sử dụng jQuery để xử lý sự kiện click trên nút "Edit"
    $(document).on("click", ".btn_edit_user", function () {
        // e.preventDefault(); // Ngăn chặn việc load trang nếu là thẻ <a>
        let userId = $(this).data("user-id");
        console.log(userId);
        $.ajax({
            url: "/users/" + userId + "/edit",
            method: "GET",
            success: function (response) {
                console.log("Dữ liệu nhận được từ server:", response);
                $(".edit-name").val(response.user.name); //val() là hàm của jQuery để đặt giá trị cho input;
                $(".edit-email").val(response.user.email);
                $("#chuc_vu").val(response.user.chucvu);
                $("#role").val(response.user.role);
                $("#trangthai").val(response.user.trang_thai);
                //$("#offcanvasEditUser").offcanvas("show");
                let donViSelect = $("#don_vi_edit");
                donViSelect.empty(); // Xóa các tùy chọn hiện tại
                response.donVis.forEach(function (donVi) {
                    let selected =
                        donVi.id === response.user.don_vi_id ? "selected" : "";
                    donViSelect.append(
                        `<option value="${donVi.id}" ${selected}>${donVi.ten_don_vi}</option>`,
                    );
                });
                //gán userId vào nút submit để sử dụng khi cập nhật
                $(".edit-submit").data("user-id", response.user.id);
                var myOffcanvas = new bootstrap.Offcanvas(
                    $("#offcanvasEditUser")[0],
                );
                myOffcanvas.show();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            },
        });
    });
    // Xử lý sự kiện click trên nút "Submit" trong form chỉnh sửa
    $(".edit-submit").click(function (e) {
        e.preventDefault(); // Ngăn chặn việc submit form truyền thống
        let userId = $(this).data("user-id");
        let formData = {
            name: $("#edit-name").val(),
            email: $("#edit-email").val(),
            chuc_vu: $("#chuc_vu").val(),
            role: $("#role").val(),
            trang_thai: $("#trangthai").val(),
            don_vi_id: $("#don_vi_edit").val(),
        };
        $.ajax({
            _token: $('meta[name="csrf-token"]').attr("content"),
            url: "/users/" + userId,
            method: "PUT",
            data: formData,
            success: function (response) {
                fetchUser(); // Gọi hàm fetchUser sau khi cập nhật người dùng thành công để cập nhật lại bảng người dùng
                let offcanvasElement =
                    document.getElementById("offcanvasEditUser");
                let instance =
                    bootstrap.Offcanvas.getInstance(offcanvasElement);

                if (instance) {
                    instance.hide();
                }
                //alert("Cập nhật người dùng thành công!");
                Swal.fire({
                    icon: "success",
                    title: "Thành công",
                    text: "Cập nhật người dùng thành công!",
                    timer: 3000,
                    showConfirmButton: false,
                    timerProgressBar: true,
                });
            },
            // Tải lại trang để hiển thị dữ liệu mới
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let message = "";
                    $.each(errors, function (key, value) {
                        message += value[0] + "\n";
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi",
                        text: message,
                        timer: 3000,
                        showConfirmButton: false,
                        timerProgressBar: true,
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi",
                        text:
                            "Đã xảy ra lỗi khi cập nhật người dùng. Vui lòng thử lại." +
                            xhr.responseJSON.message,
                    });
                }
            },
        });
    });
    // Xử lý sự kiện click trên nút "Thêm" trong form thêm người dùng
    $(".add-submit").click(function (e) {
        e.preventDefault();
        let formData = {
            name: $(".add-name").val(),
            email: $(".add-email").val(),
            chucvu: $("#chuc_vu_add").val(),
            role: $(".add-role").val(),
            don_vi_id: $(".add-donvi").val(),
            password: $(".add-password").val(),
        };
        $.ajax({
            _token: $('meta[name="csrf-token"]').attr("content"),
            url: "/users",
            method: "POST",
            data: formData,
            success: function (response) {
                fetchUser(); // Gọi hàm fetchUser sau khi thêm người dùng thành công để cập nhật lại bảng người dùng
                let offcanvasElement =
                    document.getElementById("offcanvasAddUser");
                let instance =
                    bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (instance) {
                    instance.hide();
                }
                //alert("Thêm người dùng thành công!");
                Swal.fire({
                    icon: "success",
                    title: "Thành công",
                    text: "Thêm người dùng thành công!",
                    timer: 3000,
                    showConfirmButton: false,
                    timerProgressBar: true,
                });
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    // 200 → success
                    // 422 → validation error
                    // 500 → system error
                    let errors = xhr.responseJSON.errors;
                    let message = "";
                    $.each(errors, function (key, value) {
                        message += value[0] + "\n";
                    });

                    Swal.fire({
                        icon: "error",
                        title: "Lỗi",
                        text: message,
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi",
                        text:
                            "Đã xảy ra lỗi khi cập nhật người dùng. Vui lòng thử lại." +
                            xhr.responseJSON.message,
                    });
                }
            },
        });
    });
    // Xử lý sự kiện click trên nút "Delete"
    $(document).on("click", ".btn_delete_user", function (e) {
        e.preventDefault();
        let userId = $(this).data("user-id");
        if (confirm("Bạn có chắc chắn muốn xóa người dùng này?")) {
            $.ajax({
                _token: $('meta[name="csrf-token"]').attr("content"),
                url: "/users/" + userId,
                method: "DELETE",
                success: function (response) {
                    fetchUser(); // Gọi hàm fetchUser sau khi xóa người dùng thành công để cập nhật lại bảng người dùng
                    Swal.fire({
                        icon: "success",
                        title: "Thành công",
                        text: "Xóa người dùng thành công!",
                        timer: 3000,
                        showConfirmButton: false,
                        timerProgressBar: true,
                    });
                },
            });
        }
    });
    // Xử lý sự kiện click trên nút "Import"
    // $(document).on("click", ".btn-import", function (e) {
    //     e.preventDefault();
    //     let formData = new FormData(this);
    //     // let fileInput = $("#import_file")[0];
    //     // if (fileInput.files.length === 0) {
    //     //     Swal.fire({
    //     //         icon: "error",
    //     //         title: "Lỗi",
    //     //         text: "Vui lòng chọn một tệp Excel để import.",
    //     //     });
    //     //     return;
    //     // }
    //     // formData.append("import_file", fileInput.files[0]);
    //     $.ajax({
    //         _token: $('meta[name="csrf-token"]').attr("content"),
    //         url: "/users/import",
    //         method: "POST",
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         success: function (response) {
    //             fetchUser();
    //             Swal.fire({
    //                 icon: "success",
    //                 title: "Thành công",
    //                 text: "Import người dùng thành công!",
    //             });
    //         },
    //     });
    // });
});
