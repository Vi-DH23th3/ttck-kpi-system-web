function confirmExport() {
    confirmAction({
        message: "Bạn có chắc muốn xuất file Excel?",
        icon: "question",
        confirmText: "Xuất file",
        onConfirm: () => submitExport(),
    });
}
function submitExport() {
    const form = document.getElementById("mainForm");
    const linkGoc = form.action;
    const methodGoc = form.method;

    form.action = exportRoute;
    form.method = "POST";

    form.submit();

    setTimeout(() => {
        form.action = linkGoc;
        form.method = methodGoc;
    }, 200);
}
