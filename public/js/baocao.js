$(".btn-xem-bao-cao").click(function () {
    let pcid = $(this).data("cv-id");
    let url = URL_XEM_BAO_CAO.replace(":id", pcid);
    const modalElement = document.getElementById("modalXemBaoCao");
    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

    $.ajax({
        url: url,
        method: "GET",
        success: function (res) {
            $(".body-xem-bao-cao").html(res);
            modal.show();
        },
        error: function (xhr) {
            Swal.fire({
                icon: "error",
                title: "Lỗi",
                text: "Lỗi tải báo cáo: " + xhr.statusText,
                timer: 3000,
                showConfirmButton: true,
                timerProgressBar: false,
            });
        },
    });
});
$(document).on("click", ".btn-tralaibc", function () {
    let id = $(this).data("tlid");
    $(".tlbc").val(id);
    $("#modalTraLai").modal("show");
});
