function clickAction(options = {}) {
    let {
        message = "Bạn có chắc muốn thực hiện hành động này?",
        title = "Xác nhận",
        icon = "warning",
        confirmText = "Đồng ý",
        cancelText = "Hủy",
        onConfirm = null,
    } = options;

    Swal.fire({
        title: title,
        text: message,
        icon: icon,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
    }).then((result) => {
        if (result.isConfirmed && typeof onConfirm === "function") {
            onConfirm();
        }
    });
}
function xacNhan(button) {
    console.log("clicked");
    let form = button.closest("form");

    clickAction({
        message: button.dataset.message,
        onConfirm: () => form.submit(),
    });
}
