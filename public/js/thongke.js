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
    }, 500);
}
